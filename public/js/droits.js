/**
 * @constructor
 * @this {DroitsTbl}
 * @param {string} id
 * @returns {DroitsTbl}
 */
function DroitsTbl(id)
{
    this.id = id;
    this.element = $(".droits-tbl#" + this.id);

    this.modifier = function (td, action)
    {
        var that = this;
        td.html("<div class=\"loading\">&nbsp;</div>");
        td.load(Url('droits/privileges/modifier'), {
            role: td.data("role"),
            statut: td.data("statut"),
            privilege: td.data("privilege"),
            action: action
        }, function ()
        {
            that.initModifierClick(td); // pour reconnecter l'action du lien...
        });

    }


    this.initModifierClick = function (td)
    {
        var that = this;
        td.find("a").on("click", function ()
        {
            that.modifier(td, $(this).data("action"));
        });
    }

    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    this.init = function ()
    {
        var that = this;
        this.element.find("td.modifier").each(function ()
        {
            that.initModifierClick($(this));
        });
        return this;
    }
}

/**
 *
 * @param {string} id
 * @returns {DroitsTbl}
 */
DroitsTbl.get = function (id)
{
    if (null == DroitsTbl.instances) DroitsTbl.instances = new Array();
    if (null == DroitsTbl.instances[id]) DroitsTbl.instances[id] = new DroitsTbl(id);
    return DroitsTbl.instances[id];
}


/**
 *
 * @constructor
 */
$.widget("ose.affectationForm", {

    onPersonnelChange: function (item)
    {
        this.getElementStructure().val(item.structure);
    },

    updateStructureVisibility: function ()
    {
        var roleMustHaveStructure = $.inArray(parseInt(this.getElementRole().val()), this.getRolesMustHaveStructure()) > -1;

        if (roleMustHaveStructure) {
            this.getElementStructure().parents('.form-group').show();
        } else {
            this.getElementStructure().parents('.form-group').hide();
        }
    },



    _create: function ()
    {
        var that = this;
        this.getElementRole().on('change', function () { that.updateStructureVisibility() });
        this.getElementPersonnel().on("change",function (e, item) { that.onPersonnelChange(item); });
        this.updateStructureVisibility();
    },



    //@formatter:off
    getRolesMustHaveStructure   : function () { return this.element.data('roles-must-have-structure'); },
    getElementPersonnel         : function () { return this.element.find('input[name="personnel\\[id\\]"]'); },
    getElementRole              : function () { return this.element.find('select[name="role"]'); },
    getElementStructure         : function () { return this.element.find('select[name="structure"]'); }
    //@formatter:on
});

$(function ()
{
    WidgetInitializer.add('affectation-form', 'affectationForm');
});