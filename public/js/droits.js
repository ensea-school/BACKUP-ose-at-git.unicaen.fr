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
        td.load(Url('droits/privileges/modifier'), {
            role: td.data("role"),
            statut: td.data("statut"),
            privilege: td.data("privilege"),
            action: action
        }, function ()
        {
            that.initModifierClick(td); // pour reconnecter l'action du lien...
        });

    },



    showOnlyCategorie: function (categorie)
    {
        this.element.find('tr.categorie[data-categorie!="' + categorie + '"]').hide();
    },



    showAll: function ()
    {
        this.element.find('tr.categorie').show();

        this.element.find('th.role').show();
        this.element.find('td.role').show();

        this.element.find('th.statut').show();
        this.element.find('td.statut').show();

        this.element.find('th.roles').attr('colspan', this.element.find('th.role:visible').length).show();
        this.element.find('th.statuts').attr('colspan', this.element.find('th.statut:visible').length).show();
        this.showHideSeparator();

        this.updateFirsts();
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

        this.showHideSeparator();
        this.updateFirsts();
    },



    hideRoles: function ()
    {
        this.element.find('th.role').hide();
        this.element.find('td.role').hide();
        this.element.find('th.roles').hide();
        this.showHideSeparator();
    },



    hideStatut: function (statutId)
    {
        this.element.find('th.statut[data-statut="' + statutId + '"]').hide();
        this.element.find('td.statut[data-statut="' + statutId + '"]').hide();

        var statutsCount = this.element.find('th.statut:visible').length;
        if (statutsCount > 0) {
            this.element.find('th.statuts').attr('colspan', statutsCount).show();
        } else {
            this.element.find('th.statuts').hide();
        }
        this.showHideSeparator();
        this.updateFirsts();
    },



    hideStatuts: function ()
    {
        this.element.find('th.statut').hide();
        this.element.find('td.statut').hide();
        this.element.find('th.statuts').hide();
        this.showHideSeparator();
    },



    showHideSeparator: function ()
    {
        if (this.element.find('th.roles:visible').length == 0 || this.element.find('th.statuts:visible').length == 0) {
            this.element.find('.separator').hide();
        } else {
            this.element.find('.separator').show();
        }
    },



    updateFirsts: function()
    {
        this.element.find('th.role.first').removeClass('first');
        this.element.find('th.statut.first').removeClass('first');

        this.element.find('th.role:visible:first').addClass('first');
        this.element.find('th.statut:visible:first').addClass('first');
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
            that.showOnlyCategorie($(this).data('categorie'));
        });

        this.element.find('th.role a').click(function ()
        {
            var roleId = $(this).parents('.role').data('role');
            that.hideRole(roleId);
        });

        this.element.find('th.statut a').click(function ()
        {
            var statutId = $(this).parents('.statut').data('statut');
            that.hideStatut(statutId);
        });

        this.element.find('a.roles').click(function ()
        {
            that.hideStatuts();
        });

        this.element.find('a.statuts').click(function ()
        {
            that.hideRoles();
        });

        this.element.find('.modifier').on('mouseover mouseout', function ()
        {
            if ($(this).data('statut')){
                var id = $(this).data('statut');
                var selector = 'statut';
            }else{
                var id = $(this).data('role');
                var selector = 'role';
            }

            that.element.find('.selected').removeClass('selected');

            $(this).prevAll().addBack()
                .add($(this).parent().prevAll()
                    .children(':nth-child(' + ($(this).index() + 1) + '):not(.categorie)')).toggleClass('selected');

            that.element.find('th.'+selector+'[data-'+selector+'="'+id+'"] div').toggleClass('selected');
        });

        this.element.find('a.show-all').click(function () { that.showAll();});

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
