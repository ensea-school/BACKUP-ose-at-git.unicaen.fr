import{c as m,n as j,o as c,t as l,a as s,b as h,w as p,d as _,e as f,f as x,r as g,F as b,g as k,h as A}from"./vendor-eb9f4386.js";const y={toast:()=>{alert("salut mon pote")}};$.widget("unicaen.popAjax",{popInstance:void 0,loading:!0,ajaxLoaded:!1,options:{url:void 0,title:void 0,content:void 0,confirm:!1,confirmButton:'<i class="fas fa-check"></i> OK',cancelButton:'<i class="fas fa-xmark"></i> Annuler',submitEvent:void 0,submitClose:!1,submitReload:!1,forced:!1,loadingTitle:"Chargement...",loadingContent:'<div class="loading"></div>'},_create:function(){var t=this;t.loadOptions(),this.element.prop("tagName")==="A"&&t.element.on("click",()=>!1),$("html").on("click",e=>{t.htmlClick(e)}),popoptions={html:!0,sanitize:!1,title:t.options.title?t.options.title:t.options.loadingTitle,content:t.options.content?t.options.content:t.options.loadingContent},t.popInstance=new bootstrap.Popover(t.element,popoptions),t.element[0].addEventListener("show.bs.popover",()=>{t.show(!0)}),t.element[0].addEventListener("inserted.bs.popover",()=>{var e=t.getPopoverElement().find(".popover-body");e.addClass("intranavigator"),e.on("DOMSubtreeModified",()=>{e.find(".popover-title,.page-header").length>0&&t.setContent(e.html())}),e.on("intranavigator-refresh",(n,r)=>{r.isSubmit&&t.contentSubmit(e)})}),t.element[0].addEventListener("hidden.bs.popover",()=>{t.hide(!0)})},loadOptions:function(){var t={url:"url",content:"content",title:"title",confirm:"confirm",confirmButton:"confirm-button",cancelButton:"cancel-button",submitEvent:"submit-event",submitClose:"submit-close",submitReload:"submit-reload",forced:"forced",loadingTitle:"loading-title",loadingContent:"loading-content"};for(var e in t)typeof this.element.data(t[e])<"u"&&(this.options[e]=this.element.data(t[e]));this.options.title===void 0&&(this.options.title=this.element.attr("title")),this.element.prop("tagName")==="A"&&(this.options.url=this.element.attr("href"))},ajaxLoad:function(){var t=this;this.ajaxLoaded=!0,this.setTitle(this.options.loadingTitle),this.setContent(this.options.loadingContent,!0),$.ajax({url:this.options.url,success:e=>{t.setContent(e)}})},setContent:function(t,e){var n=IntraNavigator.extractTitle(t);this.popInstance._config.content=n.content,this.popInstance.setContent(),n.title&&this.setTitle(n.title),e!==!0&&this._trigger("change",null,this)},getContent:function(){return this.popInstance._config.content},setTitle:function(t){this.options.title=t,this.popInstance._config.title=this.options.title;var e=this.getPopoverElement();if(e&&e.length==1){var n=e.find(".popover-header");n&&n.length==1&&n.html(this.options.title)}},getTitle:function(){return this.options.title},show:function(t){var e=this;(this.options.forced||!this.ajaxLoaded)&&this.options.url&&(this.options.confirm?this.setContent(this.makeConfirmBox()):(this.loading=!0,this.ajaxLoad())),t!==!0&&this.popInstance.show(),this._trigger("show",null,this),setTimeout(()=>{e.loading=!1},100)},hide:function(t){t!==!0&&this.popInstance.hide(),this.loading=!0,this._trigger("hide",null,this)},shown:function(){return this.getPopoverElement()!==void 0},hasErrors:function(){return IntraNavigator.hasErrors(this.getContent())},contentSubmit:function(t){IntraNavigator.hasErrors(t)?this._trigger("error",null,this):(this.options.submitEvent&&(this.options.submitEvent instanceof Function?(this.options.submitEvent(this),this.hide()):$("body").trigger(this.options.submitEvent,this)),this.options.submitClose&&this.hide(),this.options.submitReload&&setTimeout(()=>{window.location.reload()},500),this._trigger("submit",null,this))},makeConfirmBox:function(){var t='<form action="'+this.options.url+'" method="post">'+this.options.content+'<div class="btn-goup" style="text-align:right;padding-top: 10px" role="group">';return this.options.cancelButton&&(t+='<button type="button" class="btn btn-secondary pop-ajax-hide">'+this.options.cancelButton+"</button>"),this.options.confirmButton&&this.options.cancelButton&&(t+="&nbsp;"),this.options.confirmButton&&(t+='<button type="submit" class="btn btn-primary">'+this.options.confirmButton+"</button>"),t+="</div></form>",t},htmlClick:function(t){var e=this.getPopoverElement();if(!this.loading){if(!e||!e[0]||t.target==this.element[0])return!0;var n=e[0].getBoundingClientRect(),r=t.clientX<n.left||t.clientX>n.left+n.width||t.clientY<n.top||t.clientY>n.top+n.height,i=$(t.target).parents(".popover-content,.popover-body,.ui-autocomplete").length==0;$(t.target).hasClass("pop-ajax-hide")&&this.hide(),r&&i&&this.hide()}},getPopoverElement:function(){var t=$(this.element).attr("aria-describedby");if(t)return $("#"+t)}});$(function(){WidgetInitializer.add("pop-ajax","popAjax")});const v=(t,e)=>{const n=t.__vccOpts||t;for(const[r,i]of e)n[r]=i;return n},B={name:"icon",props:{name:{required:!0,type:String}}};function L(t,e,n,r,i,o){return c(),m("i",{class:j(`fas fa-${n.name}`)},null,2)}const w=v(B,[["render",L]]),O=Object.freeze(Object.defineProperty({__proto__:null,default:w},Symbol.toStringTag,{value:"Module"})),N={name:"Utilisateur",props:{nom:String,mail:String}},M=["href"];function P(t,e,n,r,i,o){return c(),m("a",{href:`mailto:${n.mail}`},l(n.nom),9,M)}const S=v(N,[["render",P]]),I=Object.freeze(Object.defineProperty({__proto__:null,default:S},Symbol.toStringTag,{value:"Module"})),U={name:"Mission",components:{utilisateur:S,icon:w},props:{mission:{required:!0}},data(){return{mission:this.mission,saisieUrl:Util.url("mission/saisie/:mission",{mission:this.mission.id}),supprimerUrl:Util.url("mission/supprimer/:mission",{mission:this.mission.id})}},computed:{heuresLib:function(){return this.mission.heures===null||this.mission.heures===0?"Aucune heure saisie":this.mission.heures-this.mission.heuresValidees==0?this.mission.heures+" heures validés":this.mission.heuresValidees==0?this.mission.heures+" heures à valider":this.mission.heures+" heures dont "+(this.mission.heures-this.mission.heuresValidees)+" à valider"},validation:function(){return this.mission.validation===null?"A valider":this.mission.validation.id===null?"Autovalidée":"Validation du "+this.mission.validation.histoCreation+" par "}},methods:{saisie(t){modAjax(t.target,e=>{axios.get(Util.url("mission/get/:mission",{mission:this.mission.id})).then(n=>{this.mission=n.data})})},supprimer(t){popAjax(t.target,e=>{this.$emit("supprimer",this.mission),y.toast()})},valider(){},devalider(){},test(){y.toast()}}},F=["id"],V={class:"float-end"},z={class:"card-body"},D={class:"row"},q={class:"col-md-8"},R={class:"row"},K={class:"col-md-12"},X=s("label",{class:"form-label"},"Composante en charge du suivi",-1),Y={class:"form-control"},H={class:"row"},W={class:"col-md-6"},Z=s("label",{class:"form-label"},"Taux de rémunération",-1),G={class:"form-control"},J={class:"col-md-6"},Q=s("label",{class:"form-label"},"Nombre d'heures prévisionnelles",-1),tt={class:"input-group mb-3"},et={class:"form-control"},it=s("button",{onclick:"alert('non implémenté')",class:"input-group-btn btn btn-secondary"},"Suivi",-1),st={class:"row"},nt={class:"col-md-12"},ot=s("label",{class:"form-label"},"Descriptif de la mission",-1),rt={class:"form-control"},lt=s("div",{class:"row"},[s("div",{class:"col-md-12"}," ")],-1),at={class:"row"},ut={class:"col-md-12"},ct=["href"],dt=["href"],mt={class:"col-md-4"},pt=s("div",null,[s("label",{class:"form-label"},"Suivi")],-1),ht=s("div",null," Aucune heure réalisée ",-1);function ft(t,e,n,r,i,o){const a=g("icon"),d=g("utilisateur");return c(),m("div",{id:i.mission.id,class:"card bg-default"},[s("form",{onSubmit:e[3]||(e[3]=p((...u)=>t.submitForm&&t.submitForm(...u),["prevent"]))},[s("div",{class:j(["card-header",{"bg-info":i.mission.valide}])},[h(l(i.mission.typeMission.libelle)+" ",1),s("span",V,"Du "+l(i.mission.dateDebut)+" au "+l(i.mission.dateFin),1)],2),s("div",z,[s("div",D,[s("div",q,[s("div",R,[s("div",K,[X,s("div",Y,l(i.mission.structure.libelle),1)])]),s("div",H,[s("div",W,[Z,s("div",G,l(i.mission.missionTauxRemu.libelle),1)]),s("div",J,[Q,s("div",tt,[s("div",et,l(o.heuresLib),1),it])])]),s("div",st,[s("div",nt,[ot,s("div",rt,l(i.mission.description),1)])]),lt,s("div",at,[s("div",ut,[i.mission.canEdit?(c(),m("a",{key:0,href:i.saisieUrl,class:"btn btn-primary",onClick:e[0]||(e[0]=p((...u)=>o.saisie&&o.saisie(...u),["prevent"]))},"Modifier la mission",8,ct)):_("",!0),s("a",{class:"btn btn-danger",onClick:e[1]||(e[1]=p((...u)=>t.devalidation&&t.devalidation(...u),["prevent"]))},"Dévalidation de la mission"),s("a",{class:"btn btn-danger",href:i.supprimerUrl,"data-title":"Suppression de la mission","data-content":"Êtes-vous sur de vouloir supprimer la mission ?","data-confirm":"true",onClick:e[2]||(e[2]=p((...u)=>o.supprimer&&o.supprimer(...u),["prevent"]))},"Suppression de la mission",8,dt)])])]),s("div",mt,[pt,s("div",null,[f(a,{name:"thumbs-up"}),h(" Créé le "+l(i.mission.histoCreation)+" par ",1),f(d,{nom:i.mission.histoCreateur.displayName,mail:i.mission.histoCreateur.email},null,8,["nom","mail"])]),s("div",null,[f(a,{name:i.mission.valide?"thumbs-up":"thumbs-down"},null,8,["name"]),h(" "+l(o.validation)+" ",1),i.mission.validation&&i.mission.validation.histoCreateur?(c(),x(d,{key:0,nom:i.mission.validation.histoCreateur.displayName,mail:i.mission.validation.histoCreateur.email},null,8,["nom","mail"])):_("",!0)]),s("div",null,[f(a,{name:i.mission.contrat?"thumbs-up":"thumbs-down"},null,8,["name"]),h(" "+l(i.mission.contrat?"Contrat établi":"Pas de contrat"),1)]),ht])])])],32),s("button",{onClick:e[4]||(e[4]=(...u)=>o.test&&o.test(...u))},"Test")],8,F)}const E=v(U,[["render",ft]]),vt=Object.freeze(Object.defineProperty({__proto__:null,default:E},Symbol.toStringTag,{value:"Module"})),_t={components:{mission:E},props:{intervenant:{type:Number,required:!0},canAddMission:{type:Boolean,required:!0}},data(){return{missions:[],ajoutUrl:Util.url("mission/ajout/:intervenant",{intervenant:this.intervenant})}},mounted(){axios.get(Util.url("mission/liste/:intervenant",{intervenant:this.intervenant})).then(t=>{this.missions=t.data})},methods:{ajout(t){modAjax(t.target,e=>{axios.get(Util.url("mission/get/:mission",{mission:this.mission.id})).then(n=>{this.missions.push(n.data)})})},supprimer(t){this.missions.indexOf(t)}}},gt=["href"];function bt(t,e,n,r,i,o){const a=g("mission");return c(),m(b,null,[(c(!0),m(b,null,k(i.missions,d=>(c(),x(a,{onSupprimer:o.supprimer,key:d.id,mission:d},null,8,["onSupprimer","mission"]))),128)),n.canAddMission?(c(),m("a",{key:0,class:"btn btn-primary",href:i.ajoutUrl,onClick:e[0]||(e[0]=p((...d)=>o.ajout&&o.ajout(...d),["prevent"]))},"Ajout d'une nouvelle mission",8,gt)):_("",!0)],64)}const yt=v(_t,[["render",bt]]),Ct=Object.freeze(Object.defineProperty({__proto__:null,default:yt},Symbol.toStringTag,{value:"Module"}));(function(){const e=document.createElement("link").relList;if(e&&e.supports&&e.supports("modulepreload"))return;for(const i of document.querySelectorAll('link[rel="modulepreload"]'))r(i);new MutationObserver(i=>{for(const o of i)if(o.type==="childList")for(const a of o.addedNodes)a.tagName==="LINK"&&a.rel==="modulepreload"&&r(a)}).observe(document,{childList:!0,subtree:!0});function n(i){const o={};return i.integrity&&(o.integrity=i.integrity),i.referrerpolicy&&(o.referrerPolicy=i.referrerpolicy),i.crossorigin==="use-credentials"?o.credentials="include":i.crossorigin==="anonymous"?o.credentials="omit":o.credentials="same-origin",o}function r(i){if(i.ep)return;i.ep=!0;const o=n(i);fetch(i.href,o)}})();const C=Object.assign({"./components/Application/Icon.vue":O,"./components/Application/Utilisateur.vue":I,"./components/Mission/Liste.vue":Ct,"./components/Mission/Mission.vue":vt});let jt="./components/";const T={};for(const t in C){let e=t.slice(jt.length,-4).replace("/","");T[e]=C[t].default}for(const t of document.getElementsByClassName("vue-app"))A({template:t.innerHTML,components:T}).mount(t);
