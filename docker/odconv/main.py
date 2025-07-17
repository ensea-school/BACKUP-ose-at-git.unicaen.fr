import os
import subprocess
import tempfile
import logging
import uuid
from fastapi import FastAPI, UploadFile, Form, HTTPException
from fastapi.responses import Response
from pathlib import Path

# Configurer le logging sur stdout (niveau DEBUG pour capturer tout)
logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

app = FastAPI(title="Service de Conversion LibreOffice")

@app.post("/convert")
async def convert(
    source: UploadFile = Form(...),  # Contenu binaire du fichier
    format: str = Form(...)  # Format de sortie (ex. : "pdf", "odt", "ods")
):
    logging.info(f"Requête de conversion reçue vers le format {format}")

    # Créer un dossier temporaire sécurisé
    with tempfile.TemporaryDirectory() as tmp_dir:
        # Chemin du fichier d'entrée temporaire
        input_path = Path(tmp_dir) / source.filename

        # Déduire l'extension source (ex. : "odt" de "monfichier.odt")
        source_extension = input_path.suffix.lstrip('.')  # Retire le point et donne "odt"
        if not source_extension:
            logging.error("Le nom du fichier d'entrée doit avoir une extension valide.")
            raise HTTPException(status_code=400, detail="Le nom du fichier d'entrée doit avoir une extension valide.")

        # Sauvegarder le fichier uploadé
        try:
            with open(input_path, "wb") as f:
                f.write(await source.read())
            logging.debug(f"Fichier d'entrée sauvegardé à : {input_path}")
        except Exception as e:
            logging.error(f"Erreur lors de la sauvegarde du fichier : {str(e)}")
            raise HTTPException(status_code=400, detail=f"Erreur lors de la sauvegarde du fichier : {str(e)}")

        # Chemin du fichier de sortie (LibreOffice génère <nom>.<format>)
        output_filename = f"{input_path.stem}.{format}"
        output_path = Path(tmp_dir) / output_filename

        # Commande LibreOffice : soffice --headless --convert-to <format> <input> --outdir <dir>
        try:
            logging.debug(f"Lancement de la commande LibreOffice : soffice --headless --convert-to {format} {input_path} --outdir {tmp_dir}")
            result = subprocess.run(
                [
                    "soffice",  # Binaire LibreOffice (assurez-vous qu'il est dans le PATH)
                    "--headless",
                    "--convert-to", format,
                    str(input_path),
                    "--outdir", tmp_dir
                ],
                check=True,  # Lance une exception si échec
                capture_output=True,  # Capture stdout/stderr pour debug
                timeout=300  # Timeout augmenté pour matcher Gunicorn
            )
            # Logger stdout/stderr même en succès pour debug
            logging.debug(f"LibreOffice stdout: {result.stdout.decode('utf-8')}")
            logging.debug(f"LibreOffice stderr: {result.stderr.decode('utf-8')}")
        except subprocess.CalledProcessError as e:
            error_stdout = e.stdout.decode('utf-8')
            error_stderr = e.stderr.decode('utf-8')
            error_msg = f"stdout: {error_stdout}\nstderr: {error_stderr}"
            logging.error(f"Erreur lors de la conversion LibreOffice : {error_msg}")
            raise HTTPException(status_code=500, detail=f"Erreur lors de la conversion LibreOffice : {error_msg}")
        except subprocess.TimeoutExpired:
            logging.error("Timeout lors de la conversion.")
            raise HTTPException(status_code=500, detail="Timeout lors de la conversion.")
        except Exception as e:
            logging.error(f"Erreur inattendue : {str(e)}")
            raise HTTPException(status_code=500, detail=f"Erreur inattendue : {str(e)}")

        # Vérifier si le fichier de sortie existe
        if not output_path.exists():
            logging.error("La conversion a échoué : fichier de sortie non généré.")
            raise HTTPException(status_code=500, detail="La conversion a échoué : fichier de sortie non généré.")

        logging.info(f"Conversion réussie : fichier de sortie {output_path}")

        # Lire le fichier en mémoire AVANT la sortie du bloc 'with' (pour éviter suppression)
        try:
            with open(output_path, "rb") as f:
                file_content = f.read()
        except Exception as e:
            logging.error(f"Erreur lors de la lecture du fichier de sortie : {str(e)}")
            raise HTTPException(status_code=500, detail=f"Erreur lors de la lecture du fichier de sortie : {str(e)}")

        # Retourner une Response avec le contenu binaire (au lieu de FileResponse qui dépend du chemin)
        return Response(
            content=file_content,
            media_type="application/octet-stream",
            headers={"Content-Disposition": f"attachment; filename={output_filename}"}
        )