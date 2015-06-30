/**
 * Formulaire de demande de mise en paiement
 *
 * @constructor
 * @this {DroitsTbl}
 * @param {string} id
 * @returns {DroitsTbl}
 */
function DroitsTbl( id )
{
    this.id      = id;
    this.element = $(".droits-tbl#"+this.id);

    this.modifier = function( td, action )
    {
        var that = this;
        td.html("<div class=\"loading\">&nbsp;</div>");
        td.load( Url('droits/privileges/modifier'), {
            role     : td.data("role"     ),
            statut   : td.data("statut"   ),
            privilege: td.data("privilege"),
            action   : action
        }, function(){
            that.initModifierClick(td); // pour reconnecter l'action du lien...
        } );
        
    }


    this.initModifierClick = function( td )
    {
        var that = this;
        td.find("a").on("click", function(){
            that.modifier( td, $(this).data("action") );
        });
    }

    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    this.init = function()
    {
        var that = this;
        this.element.find("td.modifier").each( function(){
            that.initModifierClick( $(this) );
        });
        return this;
    }
}

/**
 *
 * @param {string} id
 * @returns {DroitsTbl}
 */
DroitsTbl.get = function( id )
{
    if (null == DroitsTbl.instances) DroitsTbl.instances = new Array();
    if (null == DroitsTbl.instances[id]) DroitsTbl.instances[id] = new DroitsTbl(id);
    return DroitsTbl.instances[id];
}
