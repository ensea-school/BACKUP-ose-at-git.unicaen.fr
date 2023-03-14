import{o as r,c as l,a as t,b as c,w as k,v as R,F as b,r as U,d as V,t as m,n as B,e as O,f as J,g as f,h as p,i as M,j as E,k as A,l as y,m as Y,p as G,q as K,s as W,u as Q,x as X,y as Z,z as ee,A as w,B as g,C as te,D as se,E as D,G as ie}from"./vendor-b8d6c56c.js";const x=(s,e)=>{const i=s.__vccOpts||s;for(const[v,o]of e)i[v]=o;return i},ne={name:"UCalendar",props:{date:{type:Date,required:!0},events:{type:Array,required:!0},canAddEvent:{type:Boolean,required:!0,default:!0}},data(){const s=new Date(this.date);return{mois:s.getMonth()+1,annee:s.getFullYear()}},computed:{listeJours(){const s=new Date(this.date);s.setDate(1),s.setMonth(s.getMonth()+1),s.setDate(s.getDate()-1);let e=s.getDate();return Array.from({length:e},(i,v)=>v+1)}},watch:{date:function(s,e){const i=new Date(this.date);this.mois=i.getMonth()+1,this.annee=i.getFullYear()},mois:function(s,e){const i=new Date(this.date);i.setMonth(s-1),this.$emit("changeDate",i)},annee:function(s,e){const i=new Date(this.date);i.setFullYear(s),this.$emit("changeDate",i)}},methods:{nomJour(s){const e=new Date(this.date);return e.setDate(s),e.toLocaleString("fr-FR",{weekday:"short"})},listeMois(){let s=[];const e=new Date;for(let i=1;i<=12;i++){e.setMonth(i-1);let v=e.toLocaleString("fr-FR",{month:"long"});s.push({id:i,libelle:v})}return s},listeAnnees(){const e=new Date().getFullYear(),i=1;let v=[];for(let o=e-i;o<=e+i;o++)v.push(o);return v},addEvent(s){const e=new Date(this.date);e.setDate(s.currentTarget.dataset.jour),this.$emit("addEvent",e)},editEvent(s){const e=s.currentTarget.dataset.index;this.$emit("editEvent",this.events[e])},deleteEvent(s){const e=s.currentTarget.dataset.index;this.$emit("deleteEvent",this.events[e])},prevMois(){const s=new Date(this.date);s.setMonth(s.getMonth()-1),this.$emit("changeDate",s)},nextMois(){const s=new Date(this.date);s.setMonth(s.getMonth()+1),this.$emit("changeDate",s)},eventsByJour(s){const e=new Date(this.date);let i={};for(let v in this.events){let o=this.events[v];o.date.getFullYear()===e.getFullYear()&&o.date.getMonth()+1===e.getMonth()+1&&o.date.getDate()===s&&(i[v]=o)}return i}}},oe={class:"calendar"},re={class:"recherche"},le={class:"recherche btn-group"},ae=["value"],ue=["value"],de={class:"table table-bordered table-hover table-sm"},ce=["data-jour"],me={class:"nom-jour"},he={class:"numero-jour"},_e={class:"num-jour badge bg-secondary rounded-circle"},ve={class:"event-content"},fe={class:"event-actions"},pe={class:"btn-group btn-group-sm"},be=["data-index"],ge=["data-index"],ye={key:0},xe=["data-jour"];function ke(s,e,i,v,o,n){const a=V("u-icon");return r(),l("div",oe,[t("div",re,[t("div",le,[t("button",{class:"btn btn-light",id:"prevMois",onClick:e[0]||(e[0]=(...u)=>n.prevMois&&n.prevMois(...u)),title:"Mois précédant"},[c(a,{name:"chevron-left"})]),k(t("select",{class:"form-select btn btn-light",id:"otherMois","onUpdate:modelValue":e[1]||(e[1]=u=>o.mois=u)},[(r(!0),l(b,null,U(n.listeMois(),u=>(r(),l("option",{value:u.id},m(u.libelle),9,ae))),256))],512),[[R,o.mois]]),k(t("select",{class:"form-select btn btn-light",id:"otherAnnee","onUpdate:modelValue":e[2]||(e[2]=u=>o.annee=u)},[(r(!0),l(b,null,U(n.listeAnnees(),u=>(r(),l("option",{value:u},m(u),9,ue))),256))],512),[[R,o.annee]]),t("button",{class:"btn btn-light",id:"nextMois",onClick:e[3]||(e[3]=(...u)=>n.nextMois&&n.nextMois(...u)),title:"Mois suivant"},[c(a,{name:"chevron-right"})])])]),t("table",de,[(r(!0),l(b,null,U(n.listeJours,u=>(r(),l("tr",{"data-jour":u},[t("th",me,m(n.nomJour(u)),1),t("th",he,[t("div",_e,m(u<10?"0"+u.toString():u),1)]),t("td",null,[(r(!0),l(b,null,U(n.eventsByJour(u),(h,_)=>(r(),l("div",{class:"event",style:B("border-color:"+h.color),key:_},[t("div",ve,[(r(),O(J(h.component),{event:h},null,8,["event"]))]),t("div",fe,[t("div",pe,[t("button",{class:"btn btn-light",onClick:e[4]||(e[4]=(...d)=>n.editEvent&&n.editEvent(...d)),"data-index":_},[c(a,{name:"pen-to-square"})],8,be),t("button",{class:"btn btn-light",onClick:e[5]||(e[5]=(...d)=>n.deleteEvent&&n.deleteEvent(...d)),"data-index":_},[c(a,{name:"trash-can",class:"text-danger"})],8,ge)])])],4))),128)),i.canAddEvent?(r(),l("div",ye,[t("button",{onClick:e[6]||(e[6]=(...h)=>n.addEvent&&n.addEvent(...h)),"data-jour":u,class:"btn btn-light btn-sm"},[c(a,{name:"plus"}),f(" Nouvel événement ")],8,xe)])):p("",!0)])],8,ce))),256))])])}const Ue=x(ne,[["render",ke],["__scopeId","data-v-dc45b73e"]]),Ve=Object.freeze(Object.defineProperty({__proto__:null,default:Ue},Symbol.toStringTag,{value:"Module"})),Te={name:"UFormDate",props:{id:{type:String,required:!1},name:{type:String,required:!0},label:{type:String,required:!0,default:"Inconnu"},modelValue:{required:!0,default:void 0},disabled:{type:Boolean,required:!1,default:!1}},data(){return{dateVal:void 0}},watch:{modelValue:function(s){let e=s;s instanceof Date&&(e=Util.dateToString(s)),s instanceof String&&(e=s.slice(0,10)),this.dateVal=e},dateVal:function(s){this.$emit("update:modelValue",new Date(s))}}},Se={class:"mb-2"},Ce=["for"],je=["name","id","disabled"];function De(s,e,i,v,o,n){return r(),l("div",Se,[t("label",{for:i.id?i.id:i.name,class:"form-label"},m(i.label),9,Ce),k(t("input",{type:"date",name:i.name,id:i.id?i.id:i.name,class:"form-control","onUpdate:modelValue":e[0]||(e[0]=a=>o.dateVal=a),disabled:i.disabled},null,8,je),[[M,o.dateVal]])])}const L=x(Te,[["render",De]]),Me=Object.freeze(Object.defineProperty({__proto__:null,default:L},Symbol.toStringTag,{value:"Module"})),we={name:"UIcon",props:{valeur:{required:!0,type:Float64Array}},computed:{affichage:function(){return Util.formattedHeures(this.valeur,!0)}}},Oe=["innerHTML"];function Ee(s,e,i,v,o,n){return r(),l("span",{class:"heures",innerHTML:n.affichage},null,8,Oe)}const Re=x(we,[["render",Ee]]),He=Object.freeze(Object.defineProperty({__proto__:null,default:Re},Symbol.toStringTag,{value:"Module"})),Ae={name:"UIcon",props:{name:{required:!0,type:String},variant:{required:!1,type:String}}};function Ie(s,e,i,v,o,n){return r(),l("i",{class:E(`fas fa-${i.name} text-${i.variant}`)},null,2)}const q=x(Ae,[["render",Ie]]),Fe=Object.freeze(Object.defineProperty({__proto__:null,default:q},Symbol.toStringTag,{value:"Module"})),Le={name:"UModal",props:{id:{required:!0,type:String},title:{required:!0,type:String}}},qe=["id"],Pe={class:"modal-dialog"},Ne={class:"modal-content"},$e={class:"modal-header"},ze={class:"modal-title"},Be=t("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"},null,-1),Je={class:"modal-body"},Ye={class:"modal-footer"},Ge=t("button",{type:"button",class:"btn btn-secondary","data-bs-dismiss":"modal"},"Fermer",-1);function Ke(s,e,i,v,o,n){return r(),l("div",{class:"modal fade",id:i.id,tabindex:"-1","aria-hidden":"true"},[t("div",Pe,[t("div",Ne,[t("div",$e,[t("h5",ze,m(i.title),1),Be]),t("div",Je,[A(s.$slots,"body")]),t("div",Ye,[A(s.$slots,"footer"),Ge])])])],8,qe)}const We=x(Le,[["render",Ke]]),Qe=Object.freeze(Object.defineProperty({__proto__:null,default:We},Symbol.toStringTag,{value:"Module"})),Xe={name:"UTest",components:{UFormDate:L},mounted(){this.personne={nom:"LECLUSE",prenom:"Laurent",dateNaisssance:new Date("1980-09-27")}},data(){return{civilite:void 0,form:{email:"rt",name:"Coucou",food:null,checked:[]},foods:[{text:"Select One",value:null},"Carrots","Beans","Tomatoes","Corn"]}},methods:{test(){console.log("coucou")},onSubmit(s){s.preventDefault(),alert(JSON.stringify(this.form))},onReset(s){s.preventDefault(),this.form.email="",this.form.name="",this.form.food=null,this.form.checked=[],this.show=!1,this.$nextTick(()=>{this.show=!0})}}},Ze={class:"m-0"};function et(s,e,i,v,o,n){const a=Y,u=G,h=K,_=W,d=Q,S=X,$=Z,z=ee;return r(),l(b,null,[c($,{onSubmit:n.onSubmit,onReset:n.onReset},{default:y(()=>[c(u,{id:"input-group-1",label:"Email address:","label-for":"input-1",description:"We'll never share your email with anyone else."},{default:y(()=>[c(a,{id:"input-1",modelValue:o.form.email,"onUpdate:modelValue":e[0]||(e[0]=C=>o.form.email=C),type:"email",placeholder:"Enter email",required:""},null,8,["modelValue"])]),_:1}),c(u,{id:"input-group-2",label:"Your Name:","label-for":"input-2"},{default:y(()=>[c(a,{id:"input-2",modelValue:o.form.name,"onUpdate:modelValue":e[1]||(e[1]=C=>o.form.name=C),placeholder:"Enter name",required:""},null,8,["modelValue"])]),_:1}),c(u,{id:"input-group-3",label:"Food:","label-for":"input-3"},{default:y(()=>[c(h,{id:"input-3",modelValue:o.form.food,"onUpdate:modelValue":e[2]||(e[2]=C=>o.form.food=C),options:o.foods,required:""},null,8,["modelValue","options"])]),_:1}),c(u,{id:"input-group-4"},{default:y(()=>[c(d,{modelValue:o.form.checked,"onUpdate:modelValue":e[3]||(e[3]=C=>o.form.checked=C),id:"checkboxes-4"},{default:y(()=>[c(_,{value:"me"},{default:y(()=>[f("Check me out")]),_:1}),c(_,{value:"that"},{default:y(()=>[f("Check that out")]),_:1})]),_:1},8,["modelValue"])]),_:1}),c(S,{type:"submit",variant:"primary"},{default:y(()=>[f("Submit")]),_:1}),c(S,{type:"reset",variant:"danger"},{default:y(()=>[f("Reset")]),_:1})]),_:1},8,["onSubmit","onReset"]),c(z,{class:"mt-3","bg-variant":"success",header:"Form Data Result"},{default:y(()=>[t("pre",Ze,m(o.form),1)]),_:1})],64)}const tt=x(Xe,[["render",et]]),st=Object.freeze(Object.defineProperty({__proto__:null,default:tt},Symbol.toStringTag,{value:"Module"})),it={name:"Utilisateur",props:{nom:String,mail:String}},nt=["href"];function ot(s,e,i,v,o,n){return r(),l("a",{href:`mailto:${i.mail}`},m(i.nom),9,nt)}const rt=x(it,[["render",ot]]),lt=Object.freeze(Object.defineProperty({__proto__:null,default:rt},Symbol.toStringTag,{value:"Module"})),at={name:"Recherche",data(){return{searchTerm:"",noResult:0,intervenants:[],checkedTypes:["vacataire","permanent","etudiant"]}},methods:{rechercher:function(s){this.searchTerm=s.currentTarget.value,this.searchTerm==""&&(this.noResult=0),this.searchTerm!=""&&this.reload()},urlFiche(s){return"/intervenant/code:"+s+"/voir"},reload(){this.timer&&(clearTimeout(this.timer),this.timer=null),this.timer=setTimeout(()=>{axios.post(Util.url("intervenant/recherche-json"),{term:this.searchTerm}).then(s=>{let e=s.data,i=[];for(const v in e){if(e[v].typeIntervenantCode=="E"&&this.checkedTypes.includes("vacataire")){i.push(e[v]);continue}if(e[v].typeIntervenantCode=="P"&&this.checkedTypes.includes("permanent")){i.push(e[v]);continue}if(e[v].typeIntervenantCode=="S"&&this.checkedTypes.includes("etudiant")){i.push(e[v]);continue}}this.intervenants=i,this.intervenants.length==0?this.noResult=1:this.noResult=0}).catch(s=>{console.log(s.message)})},800)}}},ut=t("h3",null,"Saisissez le nom suivi éventuellement du prénom (2 lettres minimum)",-1),dt={class:"intervenant-recherche"},ct={class:"critere"},mt=t("br",null,null,-1),ht=t("span",{class:"fw-bold"},"Types d'intervenant : ",-1),_t=t("br",null,null,-1),vt={key:0,class:"table table-bordered table-hover"},ft=t("thead",null,[t("tr",null,[t("th",{style:{width:"90px"}}),t("th",null,"Civilité"),t("th",null,"Nom"),t("th",null,"Prenom"),t("th",null,"Structure"),t("th",null,"Statut"),t("th",null,"Date de naissance"),t("th",null,"N° Personnel")])],-1),pt=["title"],bt={style:{}},gt=["href"],yt=t("i",{class:"fas fa-eye"},null,-1),xt={key:1,class:"table table-bordered table-hover"},kt=t("thead",null,[t("tr",null,[t("th",{style:{width:"90px"}}),t("th",null,"Civilité"),t("th",null,"Nom"),t("th",null,"Prenom"),t("th",null,"Structure"),t("th",null,"Statut"),t("th",null,"Date de naissance"),t("th",null,"N° Personnel")])],-1),Ut=t("tbody",null,[t("tr",null,[t("td",{style:{"text-align":"center"},colspan:"8"},"Aucun intervenant trouvé")])],-1),Vt=[kt,Ut];function Tt(s,e,i,v,o,n){return r(),l(b,null,[ut,t("div",dt,[t("div",ct,[t("div",null,[t("input",{id:"term",onKeyup:e[0]||(e[0]=(...a)=>n.rechercher&&n.rechercher(...a)),class:"form-control input",type:"text",placeholder:"votre recherche..."},null,32),mt]),t("div",null,[ht,k(t("input",{onChange:e[1]||(e[1]=a=>n.reload()),type:"checkbox",name:"type[]",value:"permanent",checked:"checked","onUpdate:modelValue":e[2]||(e[2]=a=>o.checkedTypes=a)},null,544),[[w,o.checkedTypes]]),f(" Permanent "),k(t("input",{onChange:e[3]||(e[3]=a=>n.reload()),type:"checkbox",name:"type[]",value:"vacataire",checked:"checked","onUpdate:modelValue":e[4]||(e[4]=a=>o.checkedTypes=a)},null,544),[[w,o.checkedTypes]]),f(" Vacataire "),k(t("input",{onChange:e[5]||(e[5]=a=>n.reload()),type:"checkbox",name:"type[]",value:"etudiant",checked:"checked","onUpdate:modelValue":e[6]||(e[6]=a=>o.checkedTypes=a)},null,544),[[w,o.checkedTypes]]),f(" Etudiant ")]),_t])]),o.intervenants.length>0?(r(),l("table",vt,[ft,t("tbody",null,[(r(!0),l(b,null,U(o.intervenants,(a,u)=>(r(),l("tr",{class:E({"bg-danger":a.destruction!==null}),title:a.destruction!==null?"Fiche historisé":""},[t("td",bt,[t("a",{href:n.urlFiche(a.code)},[yt,f(" Fiche")],8,gt)]),t("td",null,m(a.civilite),1),t("td",null,m(a.nom),1),t("td",null,m(a.prenom),1),t("td",null,m(a.structure),1),t("td",null,m(a.statut),1),t("td",null,m(a["date-naissance"]),1),t("td",null,m(a["numero-personnel"]),1)],10,pt))),256))])])):p("",!0),o.intervenants.length==0&&o.noResult==1?(r(),l("table",xt,Vt)):p("",!0)],64)}const St=x(at,[["render",Tt]]),Ct=Object.freeze(Object.defineProperty({__proto__:null,default:St},Symbol.toStringTag,{value:"Module"}));const jt={name:"Mission",props:{mission:{required:!0}},data(){return{validationText:this.calcValidation(this.mission.validation),saisieUrl:Util.url("mission/saisie/:mission",{mission:this.mission.id}),validerUrl:Util.url("mission/valider/:mission",{mission:this.mission.id}),devaliderUrl:Util.url("mission/devalider/:mission",{mission:this.mission.id}),supprimerUrl:Util.url("mission/supprimer/:mission",{mission:this.mission.id})}},watch:{"mission.validation"(s){this.validationText=this.calcValidation(s)}},computed:{heuresLib:function(){return this.mission.heures===null||this.mission.heures===0?"Aucune heure saisie":this.mission.heures==this.mission.heuresValidees?Util.formattedHeures(this.mission.heures)+" heures (validées)":this.mission.heuresValidees==0?Util.formattedHeures(this.mission.heures)+" heures (non validées)":Util.formattedHeures(this.mission.heures)+" heures ("+Util.formattedHeures(this.mission.heuresValidees)+" validées)"}},methods:{calcValidation(s){return s===null?"A valider":s.id===null?"Autovalidée":"Validation du "+s.histoCreation+" par "},saisie(s){modAjax(s.currentTarget,e=>{this.refresh()})},supprimer(s){popConfirm(s.currentTarget,e=>{this.$emit("supprimer",this.mission)})},valider(s){popConfirm(s.currentTarget,e=>{this.$emit("refresh",e.data)})},devalider(s){popConfirm(s.currentTarget,e=>{this.$emit("refresh",e.data)})},volumeHoraireSupprimer(s){s.currentTarget.href=Util.url("mission/volume-horaire/supprimer/:missionVolumeHoraire",{missionVolumeHoraire:s.currentTarget.dataset.id}),popConfirm(s.currentTarget,e=>{this.$emit("refresh",e.data)})},volumeHoraireValider(s){s.currentTarget.href=Util.url("mission/volume-horaire/valider/:missionVolumeHoraire",{missionVolumeHoraire:s.currentTarget.dataset.id}),popConfirm(s.currentTarget,e=>{this.$emit("refresh",e.data)})},volumeHoraireDevalider(s){s.currentTarget.href=Util.url("mission/volume-horaire/devalider/:missionVolumeHoraire",{missionVolumeHoraire:s.currentTarget.dataset.id}),popConfirm(s.currentTarget,e=>{this.$emit("refresh",e.data)})},refresh(){axios.get(Util.url("mission/get/:mission",{mission:this.mission.id})).then(s=>{this.$emit("refresh",s.data)})}}},T=s=>(te("data-v-449a333e"),s=s(),se(),s),Dt=["id"],Mt={class:"card-header card-header-h3"},wt={class:"float-end"},Ot={class:"card-body"},Et={class:"row"},Rt={class:"col-md-8"},Ht={class:"row"},At={class:"col-md-12"},It=T(()=>t("label",{class:"form-label"},"Composante en charge du suivi",-1)),Ft={class:"form-control"},Lt={class:"row"},qt={class:"col-md-5"},Pt=T(()=>t("label",{class:"form-label"},"Taux de rémunération",-1)),Nt={class:"form-control"},$t={class:"col-md-7"},zt=T(()=>t("label",{class:"form-label"},"Nombre d'heures prévisionnelles",-1)),Bt={class:"input-group mb-3"},Jt=["innerHTML"],Yt=["data-bs-target"],Gt={class:"row"},Kt={class:"col-md-12"},Wt=T(()=>t("label",{class:"form-label"},"Descriptif de la mission",-1)),Qt={class:"form-control"},Xt=T(()=>t("div",{class:"row"},[t("div",{class:"col-md-12"}," ")],-1)),Zt={class:"row"},es={class:"col-md-12"},ts=["href"],ss=["href"],is=["href"],ns=["href"],os={class:"col-md-4"},rs=T(()=>t("div",null,[t("label",{class:"form-label"},"Suivi")],-1)),ls=T(()=>t("div",null," Aucune heure réalisée ",-1)),as={class:"table table-bordered table-condensed"},us=T(()=>t("thead",null,[t("tr",null,[t("th",null,"Heures"),t("th",null,"Statut"),t("th",null,"Actions")])],-1)),ds={style:{"text-align":"right"}},cs=T(()=>t("br",null,null,-1)),ms={key:0},hs=["data-id"],_s=["data-id"],vs=["data-id"];function fs(s,e,i,v,o,n){const a=V("u-icon"),u=V("utilisateur"),h=V("u-heures"),_=V("u-modal");return r(),l(b,null,[t("div",{id:i.mission.id,class:E(["card",{"bg-success":i.mission.valide,"bg-default":!i.mission.valide}])},[t("form",{onSubmit:e[4]||(e[4]=g((...d)=>s.submitForm&&s.submitForm(...d),["prevent"]))},[t("div",Mt,[t("h5",null,[f(m(i.mission.typeMission.libelle)+" ",1),t("span",wt,"Du "+m(i.mission.dateDebut)+" au "+m(i.mission.dateFin),1)])]),t("div",Ot,[t("div",Et,[t("div",Rt,[t("div",Ht,[t("div",At,[It,t("div",Ft,m(i.mission.structure.libelle),1)])]),t("div",Lt,[t("div",qt,[Pt,t("div",Nt,m(i.mission.tauxRemu.libelle),1)]),t("div",$t,[zt,t("div",Bt,[t("div",{class:"form-control",innerHTML:n.heuresLib},null,8,Jt),t("button",{class:"input-group-btn btn btn-secondary","data-bs-toggle":"modal","data-bs-target":`#details-${i.mission.id}`}," Détails ",8,Yt)])])]),t("div",Gt,[t("div",Kt,[Wt,t("div",Qt,m(i.mission.description),1)])]),Xt,t("div",Zt,[t("div",es,[i.mission.canSaisie?(r(),l("a",{key:0,href:o.saisieUrl,class:"btn btn-primary",onClick:e[0]||(e[0]=g((...d)=>n.saisie&&n.saisie(...d),["prevent"]))},"Modifier",8,ts)):p("",!0),i.mission.canValider?(r(),l("a",{key:1,href:o.validerUrl,class:"btn btn-secondary","data-title":"Validation de la mission","data-content":"Êtes-vous sur de vouloir valider la mission ?",onClick:e[1]||(e[1]=g((...d)=>n.valider&&n.valider(...d),["prevent"]))},"Valider",8,ss)):p("",!0),i.mission.canDevalider?(r(),l("a",{key:2,href:o.devaliderUrl,class:"btn btn-danger","data-title":"Dévalidation de la mission","data-content":"Êtes-vous sur de vouloir dévalider la mission ?",onClick:e[2]||(e[2]=g((...d)=>n.devalider&&n.devalider(...d),["prevent"]))},"Dévalider",8,is)):p("",!0),i.mission.canSupprimer?(r(),l("a",{key:3,href:o.supprimerUrl,class:"btn btn-danger","data-title":"Suppression de la mission","data-content":"Êtes-vous sur de vouloir supprimer la mission ?",onClick:e[3]||(e[3]=g((...d)=>n.supprimer&&n.supprimer(...d),["prevent"]))},"Supprimer",8,ns)):p("",!0)])])]),t("div",os,[rs,t("div",null,[c(a,{name:"thumbs-up",variant:"success"}),f(" Créé le "+m(i.mission.histoCreation)+" par ",1),c(u,{nom:i.mission.histoCreateur.displayName,mail:i.mission.histoCreateur.email},null,8,["nom","mail"])]),t("div",null,[c(a,{name:i.mission.valide?"thumbs-up":"thumbs-down",variant:i.mission.valide?"success":"info"},null,8,["name","variant"]),f(" "+m(o.validationText)+" ",1),i.mission.validation&&i.mission.validation.histoCreateur?(r(),O(u,{key:0,nom:i.mission.validation.histoCreateur.displayName,mail:i.mission.validation.histoCreateur.email},null,8,["nom","mail"])):p("",!0)]),t("div",null,[c(a,{name:i.mission.contrat?"thumbs-up":"thumbs-down",variant:i.mission.contrat?"success":"info"},null,8,["name","variant"]),f(" "+m(i.mission.contrat?"Contrat établi":"Pas de contrat"),1)]),ls])])])],32)],10,Dt),c(_,{id:`details-${i.mission.id}`,title:"Détail des heures prévisionnelles"},{body:y(()=>[t("table",as,[us,t("tbody",null,[(r(!0),l(b,null,U(i.mission.volumesHoraires,d=>(r(),l("tr",{key:d.id},[t("td",ds,[c(h,{valeur:d.heures},null,8,["valeur"])]),t("td",null,[c(a,{name:"thumbs-up",variant:"success"}),f(" Saisi par "),c(u,{nom:d.histoCreateur.displayName,mail:d.histoCreateur.email},null,8,["nom","mail"]),f(" le "+m(d.histoCreation)+" ",1),cs,c(a,{name:d.valide?"thumbs-up":"thumbs-down",variant:d.valide?"success":"info"},null,8,["name","variant"]),f(" "+m(d.validation&&d.validation.id==null?"Autovalidé":d.validation?"":"à valider")+" ",1),d.validation&&d.validation.histoCreateur?(r(),l("span",ms,[f(" Validé par "),c(u,{nom:d.validation.histoCreateur.displayName,mail:d.validation.histoCreateur.email},null,8,["nom","mail"]),f(" le "+m(d.validation.histoCreation),1)])):p("",!0)]),t("td",null,[d.canValider?(r(),l("a",{key:0,class:"btn btn-secondary","data-id":d.id,"data-title":"Validation du volume horaire","data-content":"Êtes-vous sur de vouloir valider ce volume horaire ?",onClick:e[5]||(e[5]=g((...S)=>n.volumeHoraireValider&&n.volumeHoraireValider(...S),["prevent"]))},"Valider",8,hs)):p("",!0),d.canDevalider?(r(),l("a",{key:1,class:"btn btn-danger","data-id":d.id,"data-title":"Dévalidation du volume horaire","data-content":"Êtes-vous sur de vouloir dévalider ce volume horaire ?",onClick:e[6]||(e[6]=g((...S)=>n.volumeHoraireDevalider&&n.volumeHoraireDevalider(...S),["prevent"]))},"Dévalider",8,_s)):p("",!0),d.canSupprimer?(r(),l("a",{key:2,class:"btn btn-danger","data-id":d.id,"data-title":"Suppression du volume horaire","data-content":"Êtes-vous sur de vouloir supprimer le volume horaire ?",onClick:e[7]||(e[7]=g((...S)=>n.volumeHoraireSupprimer&&n.volumeHoraireSupprimer(...S),["prevent"]))},"Supprimer",8,vs)):p("",!0)])]))),128))])])]),footer:y(()=>[]),_:1},8,["id"])],64)}const P=x(jt,[["render",fs],["__scopeId","data-v-449a333e"]]),ps=Object.freeze(Object.defineProperty({__proto__:null,default:P},Symbol.toStringTag,{value:"Module"})),bs={components:{mission:P},props:{intervenant:{type:Number,required:!0},canAddMission:{type:Boolean,required:!0}},data(){return{missions:[],ajoutUrl:Util.url("mission/ajout/:intervenant",{intervenant:this.intervenant})}},mounted(){this.reload()},methods:{ajout(s){modAjax(s.currentTarget,e=>{this.reload()})},supprimer(s){this.reload()},refresh(s){console.log(s);let e=Util.json.indexById(this.missions,s.id);this.missions[e]=s},reload(){axios.get(Util.url("mission/liste/:intervenant",{intervenant:this.intervenant})).then(s=>{this.missions=s.data})}}},gs=["href"];function ys(s,e,i,v,o,n){const a=V("mission");return r(),l(b,null,[(r(!0),l(b,null,U(o.missions,u=>(r(),O(a,{onSupprimer:n.supprimer,onRefresh:n.refresh,key:u.id,mission:u},null,8,["onSupprimer","onRefresh","mission"]))),128)),i.canAddMission?(r(),l("a",{key:0,class:"btn btn-primary",href:o.ajoutUrl,onClick:e[0]||(e[0]=g((...u)=>n.ajout&&n.ajout(...u),["prevent"]))},"Ajout d'une nouvelle mission",8,gs)):p("",!0)],64)}const xs=x(bs,[["render",ys]]),ks=Object.freeze(Object.defineProperty({__proto__:null,default:xs},Symbol.toStringTag,{value:"Module"})),Us={name:"SuiviEvent",props:{event:{type:Object,required:!0}},methods:{editEvent(){console.log(this.event)}}};function Vs(s,e,i,v,o,n){return m(i.event.description)}const j=x(Us,[["render",Vs]]),Ts=Object.freeze(Object.defineProperty({__proto__:null,default:j},Symbol.toStringTag,{value:"Module"})),Ss={name:"Suivi",props:{intervenant:{type:Number,required:!0},missions:{type:Object,required:!0}},mounted(){this.modal=new bootstrap.Modal(this.$refs.suiviForm.$el,{keyboard:!1})},data(){const s={component:D(j),color:"yellow",date:null,missionId:null,horaireDebut:null,horaireFin:null,heures:null,nocturne:!1,formation:!1,description:null};return{modal:null,date:new Date,newVhr:s,vhr:{...this.newVhr},vhrIndex:null,realise:[{component:D(j),color:"yellow",date:new Date(2023,1,5),missionId:null,horaireDebut:null,horaireFin:null,heures:null,nocturne:!1,formation:!1,description:"5"},{component:D(j),color:"red",date:new Date(2023,1,6),missionId:null,horaireDebut:null,horaireFin:null,heures:null,nocturne:!1,formation:!1,description:"6"},{component:D(j),date:new Date(2023,1,7),color:"#d5a515",missionId:null,horaireDebut:null,horaireFin:null,heures:null,nocturne:!1,formation:!1,description:"7"},{component:D(j),date:new Date(2023,2,8),missionId:null,horaireDebut:null,horaireFin:null,heures:null,nocturne:!1,formation:!1,description:"8"}]}},methods:{test(){let s={date:this.$refs.date};for(let e in s)console.log(e)},changeDate(s){this.date=s},addVolumeHoraire(s){this.vhr={...this.newVhr},this.vhr.date=s,this.vhrIndex=void 0,this.modal.show()},editVolumeHoraire(s){this.vhr={...s},this.vhrIndex=this.realise.indexOf(s),this.modal.show()},saveVolumeHoraire(){this.test()},deleteVolumeHoraire(s){const e=this.realise.indexOf(s);this.realise.splice(e,1),console.log(e),console.log(this.realise)}}},Cs={class:"mb-2"},js=t("label",{for:"mission",class:"form-label"},"Mission",-1),Ds=["value"],Ms={class:"row"},ws={class:"col-md-4"},Os={class:"col-md-4"},Es={class:"mb-2"},Rs=t("label",{for:"horaire-debut",class:"form-label"},"Horaire de début",-1),Hs={class:"col-md-4"},As={class:"mb-2"},Is=t("label",{for:"horaire-fin",class:"form-label"},"Horaire de fin",-1),Fs={class:"row"},Ls={class:"col-md-4"},qs={class:"mb-2"},Ps=t("label",{for:"heures",class:"form-label"},"Nombre d'heures",-1),Ns={class:"col-md-4"},$s={class:"mb-2"},zs=t("label",{class:"form-label"}," ",-1),Bs={class:"form-check"},Js=t("label",{class:"form-label",for:"nocturne"},"Horaire nocturne",-1),Ys={class:"col-md-4"},Gs={class:"mb-2"},Ks=t("label",{class:"form-label"}," ",-1),Ws={class:"form-check"},Qs=t("label",{class:"form-label",for:"formation"},"formation",-1),Xs={class:"mb-2"},Zs=t("label",{for:"description",class:"form-label"},"Description",-1);function ei(s,e,i,v,o,n){const a=V("u-calendar"),u=V("u-form-date"),h=V("u-modal");return r(),l(b,null,[c(a,{date:o.date,onChangeDate:n.changeDate,onAddEvent:n.addVolumeHoraire,onEditEvent:n.editVolumeHoraire,onDeleteEvent:n.deleteVolumeHoraire,"can-add-event":!0,events:o.realise},null,8,["date","onChangeDate","onAddEvent","onEditEvent","onDeleteEvent","events"]),c(h,{id:"suivi-form",ref:"suiviForm",title:"Suivi"},{body:y(()=>[t("div",Cs,[js,k(t("select",{name:"mission",id:"mission",class:"form-select","onUpdate:modelValue":e[0]||(e[0]=_=>o.vhr.missionId=_)},[(r(!0),l(b,null,U(i.missions,(_,d)=>(r(),l("option",{key:d,value:d},m(_),9,Ds))),128))],512),[[R,o.vhr.missionId]])]),t("div",Ms,[t("div",ws,[c(u,{name:"date",label:"Date",modelValue:o.vhr.date,"onUpdate:modelValue":e[1]||(e[1]=_=>o.vhr.date=_)},null,8,["modelValue"])]),t("div",Os,[t("div",Es,[Rs,k(t("input",{type:"time",name:"horaire-debut",id:"horaire-debut",class:"form-control","onUpdate:modelValue":e[2]||(e[2]=_=>o.vhr.horaireDebut=_)},null,512),[[M,o.vhr.horaireDebut]])])]),t("div",Hs,[t("div",As,[Is,k(t("input",{type:"time",name:"horaire-fin",id:"horaire-fin",class:"form-control","onUpdate:modelValue":e[3]||(e[3]=_=>o.vhr.horaireFin=_)},null,512),[[M,o.vhr.horaireFin]])])])]),t("div",Fs,[t("div",Ls,[t("div",qs,[Ps,k(t("input",{type:"number",step:"0.01",min:"0",name:"heures",id:"heures",class:"form-control","onUpdate:modelValue":e[4]||(e[4]=_=>o.vhr.heures=_)},null,512),[[M,o.vhr.heures]])])]),t("div",Ns,[t("div",$s,[zs,t("div",Bs,[Js,k(t("input",{type:"checkbox",class:"form-check-input",id:"nocturne","onUpdate:modelValue":e[5]||(e[5]=_=>o.vhr.nocturne=_)},null,512),[[w,o.vhr.nocturne]])])])]),t("div",Ys,[t("div",Gs,[Ks,t("div",Ws,[Qs,k(t("input",{type:"checkbox",class:"form-check-input",id:"formation","onUpdate:modelValue":e[6]||(e[6]=_=>o.vhr.formation=_)},null,512),[[w,o.vhr.formation]])])])])]),t("div",Xs,[Zs,k(t("textarea",{name:"description",id:"description",class:"form-control","onUpdate:modelValue":e[7]||(e[7]=_=>o.vhr.description=_)},null,512),[[M,o.vhr.description]])]),f(" "+m(o.vhr),1)]),footer:y(()=>[t("button",{class:"btn btn-primary",onClick:e[8]||(e[8]=(..._)=>n.saveVolumeHoraire&&n.saveVolumeHoraire(..._))},"Enregistrer")]),_:1},512)],64)}const ti=x(Ss,[["render",ei]]),si=Object.freeze(Object.defineProperty({__proto__:null,default:ti},Symbol.toStringTag,{value:"Module"})),ii={name:"Taux",components:{UIcon:q},props:{taux:{required:!0},listeTaux:{required:!0}},data(){return{saisieUrl:Util.url("taux/saisir/:tauxRemu",{tauxRemu:this.taux.id}),supprimerUrl:Util.url("taux/supprimer/:tauxRemu",{tauxRemu:this.taux.id}),ajoutValeurUrl:Util.url("taux/saisir-valeur/:tauxRemu",{tauxRemu:this.taux.id})}},methods:{saisie(s){modAjax(s.target,e=>{this.$emit("refreshListe")})},ajoutValeur(s){modAjax(s.target,e=>{this.$emit("refreshListe")})},saisieValeur(s){s.currentTarget.href=Util.url("taux/saisir-valeur/:tauxRemu/:tauxRemuValeur",{tauxRemu:this.taux.id,tauxRemuValeur:s.currentTarget.dataset.id}),modAjax(s.currentTarget,e=>{this.$emit("refreshListe")})},refreshListe(s){this.$emit("refreshListe")},supprimer(s){popConfirm(s.target,e=>{this.$emit("refreshListe")})},supprimerValeur(s){s.currentTarget.href=Util.url("taux/supprimer-valeur/:tauxRemuValeur",{tauxRemuValeur:s.currentTarget.dataset.id}),popConfirm(s.currentTarget,e=>{this.$emit("refreshListe")})},refresh(s){axios.get(Util.url("taux/get/:tauxRemu",{tauxRemu:s.id})).then(e=>{this.$emit("refresh",e.data)})}}},ni={class:"card-header"},oi={style:{display:"inline"}},ri={class:"float-end"},li=["href"],ai=["href"],ui={class:"card-body"},di={key:0},ci=t("br",null,null,-1),mi={class:""},hi={class:"row align-items-start"},_i={class:"col-md-4"},vi={class:"col"},fi=["data-id"],pi=["data-id"],bi=["href"],gi={key:1,class:"row"},yi={class:"col-md-7"},xi=t("br",null,null,-1),ki={class:"row align-items-start"},Ui={class:"col-md-8"},Vi={class:"col-md-auto"},Ti=["data-id"],Si=["data-id"],Ci=["href"],ji={class:"col"},Di=t("br",null,null,-1),Mi={key:0},wi={key:0};function Oi(s,e,i,v,o,n){const a=V("u-icon"),u=V("taux",!0);return r(),l(b,null,[t("div",{class:E(["card",{"ms-5":i.taux.tauxRemu}])},[t("div",ni,[t("h3",oi,m(i.taux.libelle)+" ("+m(i.taux.code)+")",1),t("div",ri,[i.taux.canEdit?(r(),l("a",{key:0,href:o.saisieUrl,class:"btn btn-primary",onClick:e[0]||(e[0]=g((...h)=>n.saisie&&n.saisie(...h),["prevent"]))},[c(a,{name:"pen-to-square"}),f(" Modifier")],8,li)):p("",!0),f("   "),i.taux.canDelete?(r(),l("a",{key:1,href:o.supprimerUrl,class:"btn btn-danger",onClick:e[1]||(e[1]=g((...h)=>n.supprimer&&n.supprimer(...h),["prevent"]))},[c(a,{name:"trash-can"}),f(" Supprimer")],8,ai)):p("",!0)])]),t("div",ui,[i.taux.tauxRemu?p("",!0):(r(),l("div",di,[f(" Modification :"),ci,t("ul",null,[(r(!0),l(b,null,U(i.taux.tauxRemuValeurs,h=>(r(),l("div",{key:h.id},[t("li",mi,[t("div",hi,[t("div",_i,m(h.valeur)+"€/h à partir du "+m(h.dateEffet),1),t("div",vi,[i.taux.canEdit?(r(),l("a",{key:0,class:"text-primary",onClick:e[2]||(e[2]=g((..._)=>n.saisieValeur&&n.saisieValeur(..._),["prevent"])),"data-id":h.id},[c(a,{name:"pen-to-square"})],8,fi)):p("",!0),f("   "),i.taux.canEdit?(r(),l("a",{key:1,class:"text-primary",onClick:e[3]||(e[3]=g((..._)=>n.supprimerValeur&&n.supprimerValeur(..._),["prevent"])),"data-id":h.id},[c(a,{name:"trash-can"})],8,pi)):p("",!0)])])])]))),128))]),i.taux.canEdit?(r(),l("a",{key:0,href:o.ajoutValeurUrl,class:"btn btn-primary btn-sm",onClick:e[4]||(e[4]=g((...h)=>n.ajoutValeur&&n.ajoutValeur(...h),["prevent"]))},[c(a,{name:"plus"}),f(" Ajouter une valeur ")],8,bi)):p("",!0)])),i.taux.tauxRemu?(r(),l("div",gi,[t("div",yi,[f(" Modification :"),xi,t("ul",null,[(r(!0),l(b,null,U(i.taux.tauxRemuValeurs,h=>(r(),l("div",null,[t("li",null,[t("div",ki,[t("div",Ui," Coéfficient de "+m(h.valeur)+" à partir du "+m(h.dateEffet),1),t("div",Vi,[i.taux.canEdit?(r(),l("a",{key:0,class:"text-primary",onClick:e[5]||(e[5]=g((..._)=>n.saisieValeur&&n.saisieValeur(..._),["prevent"])),"data-id":h.id},[c(a,{name:"pen-to-square"})],8,Ti)):p("",!0),f("   "),i.taux.canEdit?(r(),l("a",{key:1,class:"text-primary",onClick:e[6]||(e[6]=g((..._)=>n.supprimerValeur&&n.supprimerValeur(..._),["prevent"])),"data-id":h.id},[c(a,{name:"trash-can"})],8,Si)):p("",!0)])])])]))),256))]),i.taux.canEdit?(r(),l("a",{key:0,href:o.ajoutValeurUrl,class:"btn btn-primary btn-sm",onClick:e[7]||(e[7]=g((...h)=>n.ajoutValeur&&n.ajoutValeur(...h),["prevent"]))},[c(a,{name:"plus"})],8,Ci)):p("",!0)]),t("div",ji,[f(" Valeurs calculées (indexées sur le taux "+m(i.taux.tauxRemu.libelle)+") : ",1),t("ul",null,[(r(!0),l(b,null,U(i.taux.tauxRemuValeursIndex,h=>(r(),l("div",null,[t("li",null,m(h.valeur)+"€/h à partir du "+m(h.date),1)]))),256))]),Di])])):p("",!0)])],2),i.taux.tauxRemu?p("",!0):(r(),l("div",Mi,[(r(!0),l(b,null,U(i.listeTaux,h=>(r(),l("div",{key:h},[h.tauxRemu&&h.tauxRemu.id===i.taux.id?(r(),l("div",wi,[(r(),O(u,{onSupprimer:n.supprimer,onRefreshListe:n.refreshListe,key:i.taux.id,taux:h,listeTaux:i.listeTaux},null,8,["onSupprimer","onRefreshListe","taux","listeTaux"]))])):p("",!0)]))),128))]))],64)}const N=x(ii,[["render",Oi]]),Ei=Object.freeze(Object.defineProperty({__proto__:null,default:N},Symbol.toStringTag,{value:"Module"})),Ri={components:{taux:N},props:{canEditTaux:{type:Boolean,required:!0}},data(){return{listeTaux:[],ajoutUrl:Util.url("taux/saisir")}},mounted(){this.reload()},methods:{ajout(s){modAjax(s.currentTarget,e=>{this.reload()})},supprimer(){this.reload()},refreshListe(){this.reload()},refresh(s){let e=Util.json.indexById(this.listeTaux,s.id);this.listeTaux[e]=s},reload(){axios.get(Util.url("taux/liste-taux")).then(s=>{this.listeTaux=s.data})}}},Hi=["href"];function Ai(s,e,i,v,o,n){const a=V("taux");return r(),l(b,null,[(r(!0),l(b,null,U(o.listeTaux,u=>(r(),l("div",null,[u.tauxRemu?p("",!0):(r(),O(a,{onSupprimer:n.supprimer,onRefreshListe:n.refreshListe,key:u.id,taux:u,listeTaux:o.listeTaux},null,8,["onSupprimer","onRefreshListe","taux","listeTaux"]))]))),256)),i.canEditTaux?(r(),l("a",{key:0,class:"btn btn-primary",href:o.ajoutUrl,onClick:e[0]||(e[0]=g((...u)=>n.ajout&&n.ajout(...u),["prevent"]))},"Ajout d'un nouveau taux",8,Hi)):p("",!0)],64)}const Ii=x(Ri,[["render",Ai]]),Fi=Object.freeze(Object.defineProperty({__proto__:null,default:Ii},Symbol.toStringTag,{value:"Module"})),I={uIcon:"Application/UI/UIcon",uHeures:"Application/UI/UHeures",uModal:"Application/UI/UModal",uCalendar:"Application/UI/UCalendar",utilisateur:"Application/Utilisateur"},F=Object.assign({"./Application/UI/UCalendar.vue":Ve,"./Application/UI/UFormDate.vue":Me,"./Application/UI/UHeures.vue":He,"./Application/UI/UIcon.vue":Fe,"./Application/UI/UModal.vue":Qe,"./Application/UTest.vue":st,"./Application/Utilisateur.vue":lt,"./Intervenant/Recherche.vue":Ct,"./Mission/Liste.vue":ks,"./Mission/Mission.vue":ps,"./Mission/Suivi.vue":si,"./Mission/SuiviEvent.vue":Ts,"./Paiement/ListeTaux.vue":Fi,"./Paiement/Taux.vue":Ei});let Li="./";const H={};for(const s in F){let i=s.slice(Li.length,-4).replace("/","");H[i]=F[s].default}for(const s of document.getElementsByClassName("vue-app")){let e=ie({template:s.innerHTML,components:H});for(const i in I){let v=I[i].replace("/","");e.component(i,H[v])}e.mount(s)}
