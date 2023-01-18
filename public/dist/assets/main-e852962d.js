import{t as f,c as d,a as o,w as a,v as b,F as p,r as v,b as h,d as _,e as j,f as k,g as M,o as l,h as T,i as V,j as N}from"./vendor-5a215836.js";const y=(n,s)=>{const r=n.__vccOpts||n;for(const[u,e]of s)r[u]=e;return r},L={name:"Popover",props:{title:String}};function U(n,s,r,u,e,i){return f(r.title)+" Mon popover super "}const x=y(L,[["render",U]]),F=Object.freeze(Object.defineProperty({__proto__:null,default:x},Symbol.toStringTag,{value:"Module"})),P={name:"Mission",components:{popover:x},props:{mission:{required:!0},options:{type:Object}},data(){return{mission:this.mission}},methods:{submitForm(n){axios.post(Util.url("mission/modifier"),this.mission,{submitter:n.submitter}).then(s=>{this.mission=s.data})},deleteMission(n){this.$emit("delete",this.mission)}}},w=["id"],D={class:"card-header form-inline"},A=["value"],B={class:"card-body"},C={class:"row"},q={class:"col-md-6"},E={class:"mb-2"},R=o("label",{class:"form-label",for:"structure"},"Composante en charge du suivi de mission",-1),z=["value"],I={class:"col-md-3"},H={class:"mb-2"},K=o("label",{class:"form-label",for:"missionTauxRemu"},"Taux de rémunération",-1),G=["value"],J={class:"col-md-3"},Q={class:"mb-2"},W=o("label",{class:"form-label",for:"heures"},"Heures",-1),X={class:"row"},Y={class:"col-md-12"},Z={class:"mb-2"},$=o("label",{class:"form-label",for:"description"},"Descriptif de la mission",-1),ee={class:"row"},se={class:"col-md-12"},oe={class:"mb-2"},te=o("input",{type:"submit",class:"btn btn-primary",value:"Enregistrer"},null,-1);function ie(n,s,r,u,e,i){const m=M("popover");return l(),d("div",{id:e.mission.id,class:"card bg-default"},[o("form",{onSubmit:s[8]||(s[8]=j((...t)=>i.submitForm&&i.submitForm(...t),["prevent"]))},[o("div",D,[a(o("select",{class:"form-select","onUpdate:modelValue":s[0]||(s[0]=t=>e.mission.typeMission=t)},[(l(!0),d(p,null,v(r.options.typeMission,(t,c)=>(l(),d("option",{key:c,value:c},f(t),9,A))),128))],512),[[b,e.mission.typeMission]]),h("  , du "),a(o("input",{type:"date",class:"form-control","onUpdate:modelValue":s[1]||(s[1]=t=>e.mission.dateDebut=t)},null,512),[[_,e.mission.dateDebut]]),h("  au "),a(o("input",{type:"date",class:"form-control","onUpdate:modelValue":s[2]||(s[2]=t=>e.mission.dateFin=t)},null,512),[[_,e.mission.dateFin]])]),o("div",B,[o("div",C,[o("div",q,[o("div",E,[R,a(o("select",{class:"form-select","onUpdate:modelValue":s[3]||(s[3]=t=>e.mission.structure=t)},[(l(!0),d(p,null,v(r.options.structure,(t,c)=>(l(),d("option",{key:c,value:c},f(t),9,z))),128))],512),[[b,e.mission.structure]])])]),o("div",I,[o("div",H,[K,a(o("select",{class:"form-select","onUpdate:modelValue":s[4]||(s[4]=t=>e.mission.missionTauxRemu=t)},[(l(!0),d(p,null,v(r.options.missionTauxRemu,(t,c)=>(l(),d("option",{key:c,value:c},f(t),9,G))),128))],512),[[b,e.mission.missionTauxRemu]])])]),o("div",J,[o("div",Q,[W,a(o("input",{class:"form-control",type:"text","onUpdate:modelValue":s[5]||(s[5]=t=>e.mission.heures=t)},null,512),[[_,e.mission.heures]])])])]),o("div",X,[o("div",Y,[o("div",Z,[$,a(o("input",{class:"form-control",type:"text","onUpdate:modelValue":s[6]||(s[6]=t=>e.mission.description=t)},null,512),[[_,e.mission.description]])])])]),o("div",ee,[o("div",se,[o("div",oe,[te,h("   "),o("a",{class:"btn btn-danger",onClick:s[7]||(s[7]=(...t)=>i.deleteMission&&i.deleteMission(...t))},"Suppression de la mission")])])])])],32),k(m,{title:"Mon titre cool"})],8,w)}const O=y(P,[["render",ie]]),ne=Object.freeze(Object.defineProperty({__proto__:null,default:O},Symbol.toStringTag,{value:"Module"})),re={components:{mission:O},props:{intervenant:{type:Number,required:!0},canAddMission:{type:Boolean,required:!0},options:{type:Object}},data(){return{missions:[],nextMissionId:-1}},methods:{addMission(){this.missions.push({id:this.nextMissionId--})},deleteMission(n){const s=this.missions.indexOf(n);this.missions.splice(s,1)}},mounted(){axios.get(Util.url("mission/liste/:intervenant",{intervenant:this.intervenant})).then(n=>{this.missions=n.data})}};function le(n,s,r,u,e,i){const m=M("mission");return l(),d(p,null,[(l(!0),d(p,null,v(e.missions,t=>(l(),V(m,{onDelete:i.deleteMission,key:t.id,options:r.options,mission:t},null,8,["onDelete","options","mission"]))),128)),r.canAddMission?(l(),d("a",{key:0,class:"btn btn-primary",onClick:s[0]||(s[0]=(...t)=>i.addMission&&i.addMission(...t))},"Ajout d'une nouvelle mission")):T("",!0)],64)}const de=y(re,[["render",le]]),ce=Object.freeze(Object.defineProperty({__proto__:null,default:de},Symbol.toStringTag,{value:"Module"}));(function(){const s=document.createElement("link").relList;if(s&&s.supports&&s.supports("modulepreload"))return;for(const e of document.querySelectorAll('link[rel="modulepreload"]'))u(e);new MutationObserver(e=>{for(const i of e)if(i.type==="childList")for(const m of i.addedNodes)m.tagName==="LINK"&&m.rel==="modulepreload"&&u(m)}).observe(document,{childList:!0,subtree:!0});function r(e){const i={};return e.integrity&&(i.integrity=e.integrity),e.referrerpolicy&&(i.referrerPolicy=e.referrerpolicy),e.crossorigin==="use-credentials"?i.credentials="include":e.crossorigin==="anonymous"?i.credentials="omit":i.credentials="same-origin",i}function u(e){if(e.ep)return;e.ep=!0;const i=r(e);fetch(e.href,i)}})();const g=Object.assign({"./components/Application/Popover.vue":F,"./components/Mission/Liste.vue":ce,"./components/Mission/Mission.vue":ne});let ue="./components/";const S={};for(const n in g){let s=n.slice(ue.length,-4).replace("/","");S[s]=g[n].default}for(const n of document.getElementsByClassName("vue-app"))N({template:n.innerHTML,components:S}).mount(n);
