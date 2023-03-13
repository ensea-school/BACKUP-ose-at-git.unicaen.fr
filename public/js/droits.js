/**
 * @constructor
 * @this {DroitsTbl}
 * @param {string} id
 * @returns {DroitsTbl}
 */
$.widget("ose.droitsTbl", {

    modifier: function (td, action)
    {
        var that = this;
        td.html("<div class=\"loading\">&nbsp;</div>");
        td.load(Util.url('droits/privileges/modifier'), {
            role: td.data("role"),
            privilege: td.data("privilege"),
            action: action
        }, function ()
        {
            that.initModifierClick(td); // pour reconnecter l'action du lien...
        });

    },


    showHideCategorie: function (categorie)
    {
        this.element.find('tr.categorie[data-categorie="' + categorie + '"]').each(function () {

            if ($(this).is(':visible')) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    },



    showCategorie: function (categorie)
    {
        console.log(categorie);
        this.element.find('tr.categorie[data-categorie!="' + categorie + '"]').hide();
        this.element.find('tr.categorie[data-categorie="' + categorie + '"]').show();
    },


    hideRole: function (roleId)
    {
        this.element.find('th.role[data-role="' + roleId + '"]').hide();
        this.element.find('td.role[data-role="' + roleId + '"]').hide();

        var rolesCount = this.element.find('th.role:visible').length;
        if (rolesCount > 0) {
            this.element.find('th.roles').attr('colspan', rolesCount).show();
        } else {
            this.element.find('th.roles').hide();
        }

        this.updateFirsts();
    },



    updateFirsts: function ()
    {
        this.element.find('th.role.first').removeClass('first');

        this.element.find('th.role:visible:first').addClass('first');
    },



    initModifierClick: function (td)
    {
        var that = this;
        td.find("a").on("click", function ()
        {
            that.modifier(td, $(this).data("action"));
        });
    },



    /**
     * Initialisation
     *
     * @returns {undefined}
     */
    _create: function ()
    {
        var that = this;
        this.element.find("td.modifier").each(function ()
        {
            that.initModifierClick($(this));
        });

        this.element.find('a.categorie').click(function ()
        {
            that.showHideCategorie($(this).data('categorie'));
        });

        this.element.find('th.role a').click(function ()
        {
            var roleId = $(this).parents('.role').data('role');
            that.hideRole(roleId);
        });

        this.element.find('select.categorie').change(function () {
            that.showCategorie($(this).val());
        });

        this.element.find('.modifier').on('mouseover mouseout', function ()
        {

            var id = $(this).data('role');
            var selector = 'role';

            that.element.find('.selected').removeClass('selected');

            $(this).prevAll().addBack()
                .add($(this).parent().prevAll()
                    .children(':nth-child(' + ($(this).index() + 1) + '):not(.categorie)')).toggleClass('selected');

            that.element.find('th.' + selector + '[data-' + selector + '="' + id + '"] div').toggleClass('selected');
        });

        this.updateFirsts();
    }
});





/**
 *
 * @constructor
 */
$.widget("ose.affectationForm", {

    onUtilisateurChange: function (item)
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
        this.getElementUtilisateur().on("change", function (e, item) { that.onUtilisateurChange(item); });
        this.updateStructureVisibility();
    },



    //@formatter:off
    getRolesMustHaveStructure   : function () { return this.element.data('roles-must-have-structure'); },
    getElementUtilisateur         : function () { return this.element.find('input[name="utilisateur\\[id\\]"]'); },
    getElementRole              : function () { return this.element.find('select[name="role"]'); },
    getElementStructure         : function () { return this.element.find('select[name="structure"]'); }
    //@formatter:on
});
