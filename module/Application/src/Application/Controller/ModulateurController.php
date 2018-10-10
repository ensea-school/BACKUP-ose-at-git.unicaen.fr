<!DOCTYPE html>
<html class="" lang="fr">
<head prefix="og: http://ogp.me/ns#">
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="object" property="og:type">
<meta content="GitLab" property="og:site_name">
<meta content="module/Application/src/Application/Controller/ModulateurController.php · master · open-source / OSE" property="og:title">
<meta content="Organisation des Services d&#39;Enseignement" property="og:description">
<meta content="/uploads/-/system/project/avatar/57/Logo.png" property="og:image">
<meta content="64" property="og:image:width">
<meta content="64" property="og:image:height">
<meta content="https://git.unicaen.fr/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php" property="og:url">
<meta content="summary" property="twitter:card">
<meta content="module/Application/src/Application/Controller/ModulateurController.php · master · open-source / OSE" property="twitter:title">
<meta content="Organisation des Services d&#39;Enseignement" property="twitter:description">
<meta content="/uploads/-/system/project/avatar/57/Logo.png" property="twitter:image">

<title>module/Application/src/Application/Controller/ModulateurController.php · master · open-source / OSE · GitLab</title>
<meta content="Organisation des Services d&#39;Enseignement" name="description">
<link rel="shortcut icon" type="image/png" href="/assets/favicon-7901bd695fb93edb07975966062049829afb56cf11511236e61bcf425070e36e.png" id="favicon" data-original-href="/assets/favicon-7901bd695fb93edb07975966062049829afb56cf11511236e61bcf425070e36e.png" />
<link rel="stylesheet" media="all" href="/assets/application-e9df191d9f0417750d8d3e3d5c894a9e2166a68225e23d62b7fff88e7930bf43.css" />
<link rel="stylesheet" media="print" href="/assets/print-c8ff536271f8974b8a9a5f75c0ca25d2b8c1dceb4cff3c01d1603862a0bdcbfc.css" />


<script>
//<![CDATA[
window.gon={};gon.api_version="v4";gon.default_avatar_url="https://git.unicaen.fr/assets/no_avatar-849f9c04a3a0d0cea2424ae97b27447dc64a7dbfae83c036c45b403392f0e8ba.png";gon.max_file_size=10;gon.asset_host=null;gon.webpack_public_path="/assets/webpack/";gon.relative_url_root="";gon.shortcuts_path="/help/shortcuts";gon.user_color_scheme="white";gon.gitlab_url="https://git.unicaen.fr";gon.revision="30f019d";gon.gitlab_logo="/assets/gitlab_logo-7ae504fe4f68fdebb3c2034e36621930cd36ea87924c11ff65dbcb8ed50dca58.png";gon.sprite_icons="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg";gon.sprite_file_icons="/assets/file_icons-7262fc6897e02f1ceaf8de43dc33afa5e4f9a2067f4f68ef77dcc87946575e9e.svg";gon.emoji_sprites_css_path="/assets/emoji_sprites-289eccffb1183c188b630297431be837765d9ff4aed6130cf738586fb307c170.css";gon.test_env=false;gon.suggested_label_colors=["#0033CC","#428BCA","#44AD8E","#A8D695","#5CB85C","#69D100","#004E00","#34495E","#7F8C8D","#A295D6","#5843AD","#8E44AD","#FFECDB","#AD4363","#D10069","#CC0033","#FF0000","#D9534F","#D1D100","#F0AD4E","#AD8D43"];gon.current_user_id=15;gon.current_username="zvenigorosky";gon.current_user_fullname="Alexandre Zvenigorosky";gon.current_user_avatar_url="https://secure.gravatar.com/avatar/fe025d6df04d6371a542d7aa33eb409d?s=80\u0026d=identicon";
//]]>
</script>
<script src="/assets/locale/fr/app-e2186e1d4ab9c7cbd7e68154144754f57ba13c0a9ce62732c85db5c799155a99.js" defer="defer"></script>

<script src="/assets/webpack/runtime.7fe6b451.bundle.js" defer="defer"></script>
<script src="/assets/webpack/main.4924b3c5.chunk.js" defer="defer"></script>
<script src="/assets/webpack/commons~pages.projects~pages.projects.activity~pages.projects.artifacts.browse~pages.projects.artifa~1485fd35.1d5a6728.chunk.js" defer="defer"></script>
<script src="/assets/webpack/commons~pages.groups.milestones.edit~pages.groups.milestones.new~pages.projects.blame.show~pages.pro~e382f304.4661c8ac.chunk.js" defer="defer"></script>
<script src="/assets/webpack/pages.projects.blob.show.6e106491.chunk.js" defer="defer"></script>
<script>
  window.uploads_path = "/open-source/OSE/uploads";
</script>

<meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="LV7TbIsRTdyeE2rp7ymbvllxFQuqKGCptut+WueUnf/LNJEVH6rYRGKgnPmkoZ79oZwI65kAWlbbtNVAAV4DlQ==" />
<meta content="origin-when-cross-origin" name="referrer">
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<meta content="#474D57" name="theme-color">
<link rel="apple-touch-icon" type="image/x-icon" href="/assets/touch-icon-iphone-5a9cee0e8a51212e70b90c87c12f382c428870c0ff67d1eb034d884b78d2dae7.png" />
<link rel="apple-touch-icon" type="image/x-icon" href="/assets/touch-icon-ipad-a6eec6aeb9da138e507593b464fdac213047e49d3093fc30e90d9a995df83ba3.png" sizes="76x76" />
<link rel="apple-touch-icon" type="image/x-icon" href="/assets/touch-icon-iphone-retina-72e2aadf86513a56e050e7f0f2355deaa19cc17ed97bbe5147847f2748e5a3e3.png" sizes="120x120" />
<link rel="apple-touch-icon" type="image/x-icon" href="/assets/touch-icon-ipad-retina-8ebe416f5313483d9c1bc772b5bbe03ecad52a54eba443e5215a22caed2a16a2.png" sizes="152x152" />
<link color="rgb(226, 67, 41)" href="/assets/logo-d36b5212042cebc89b96df4bf6ac24e43db316143e89926c0db839ff694d2de4.svg" rel="mask-icon">
<meta content="/assets/msapplication-tile-1196ec67452f618d39cdd85e2e3a542f76574c071051ae7effbfde01710eb17d.png" name="msapplication-TileImage">
<meta content="#30353E" name="msapplication-TileColor">



</head>

<body class="ui-indigo " data-find-file="/open-source/OSE/find_file/master" data-group="" data-page="projects:blob:show" data-project="OSE">


<header class="navbar navbar-gitlab qa-navbar navbar-expand-sm">
<a class="sr-only gl-accessibility" href="#content-body" tabindex="1">Skip to content</a>
<div class="container-fluid">
<div class="header-content">
<div class="title-container">
<h1 class="title">
<a title="Tableau de bord" id="logo" href="/"><svg width="24" height="24" class="tanuki-logo" viewBox="0 0 36 36">
  <path class="tanuki-shape tanuki-left-ear" fill="#e24329" d="M2 14l9.38 9v-9l-4-12.28c-.205-.632-1.176-.632-1.38 0z"/>
  <path class="tanuki-shape tanuki-right-ear" fill="#e24329" d="M34 14l-9.38 9v-9l4-12.28c.205-.632 1.176-.632 1.38 0z"/>
  <path class="tanuki-shape tanuki-nose" fill="#e24329" d="M18,34.38 3,14 33,14 Z"/>
  <path class="tanuki-shape tanuki-left-eye" fill="#fc6d26" d="M18,34.38 11.38,14 2,14 6,25Z"/>
  <path class="tanuki-shape tanuki-right-eye" fill="#fc6d26" d="M18,34.38 24.62,14 34,14 30,25Z"/>
  <path class="tanuki-shape tanuki-left-cheek" fill="#fca326" d="M2 14L.1 20.16c-.18.565 0 1.2.5 1.56l17.42 12.66z"/>
  <path class="tanuki-shape tanuki-right-cheek" fill="#fca326" d="M34 14l1.9 6.16c.18.565 0 1.2-.5 1.56L18 34.38z"/>
</svg>

<span class="logo-text d-none d-sm-block">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 617 169"><path d="M315.26 2.97h-21.8l.1 162.5h88.3v-20.1h-66.5l-.1-142.4M465.89 136.95c-5.5 5.7-14.6 11.4-27 11.4-16.6 0-23.3-8.2-23.3-18.9 0-16.1 11.2-23.8 35-23.8 4.5 0 11.7.5 15.4 1.2v30.1h-.1m-22.6-98.5c-17.6 0-33.8 6.2-46.4 16.7l7.7 13.4c8.9-5.2 19.8-10.4 35.5-10.4 17.9 0 25.8 9.2 25.8 24.6v7.9c-3.5-.7-10.7-1.2-15.1-1.2-38.2 0-57.6 13.4-57.6 41.4 0 25.1 15.4 37.7 38.7 37.7 15.7 0 30.8-7.2 36-18.9l4 15.9h15.4v-83.2c-.1-26.3-11.5-43.9-44-43.9M557.63 149.1c-8.2 0-15.4-1-20.8-3.5V70.5c7.4-6.2 16.6-10.7 28.3-10.7 21.1 0 29.2 14.9 29.2 39 0 34.2-13.1 50.3-36.7 50.3m9.2-110.6c-19.5 0-30 13.3-30 13.3v-21l-.1-27.8h-21.3l.1 158.5c10.7 4.5 25.3 6.9 41.2 6.9 40.7 0 60.3-26 60.3-70.9-.1-35.5-18.2-59-50.2-59M77.9 20.6c19.3 0 31.8 6.4 39.9 12.9l9.4-16.3C114.5 6 97.3 0 78.9 0 32.5 0 0 28.3 0 85.4c0 59.8 35.1 83.1 75.2 83.1 20.1 0 37.2-4.7 48.4-9.4l-.5-63.9V75.1H63.6v20.1h38l.5 48.5c-5 2.5-13.6 4.5-25.3 4.5-32.2 0-53.8-20.3-53.8-63-.1-43.5 22.2-64.6 54.9-64.6M231.43 2.95h-21.3l.1 27.3v94.3c0 26.3 11.4 43.9 43.9 43.9 4.5 0 8.9-.4 13.1-1.2v-19.1c-3.1.5-6.4.7-9.9.7-17.9 0-25.8-9.2-25.8-24.6v-65h35.7v-17.8h-35.7l-.1-38.5M155.96 165.47h21.3v-124h-21.3v124M155.96 24.37h21.3V3.07h-21.3v21.3"/></svg>

</span>
</a></h1>
<ul class="list-unstyled navbar-sub-nav">
<li id="nav-projects-dropdown" class="home dropdown header-projects qa-projects-dropdown"><button data-toggle="dropdown" type="button">
Projets
<svg class=" caret-down"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-down"></use></svg>
</button>
<div class="dropdown-menu frequent-items-dropdown-menu">
<div class="frequent-items-dropdown-container">
<div class="frequent-items-dropdown-sidebar qa-projects-dropdown-sidebar">
<ul>
<li class=""><a class="qa-your-projects-link" href="/dashboard/projects">Vos projets
</a></li><li class=""><a href="/dashboard/projects/starred">Projets favoris
</a></li><li class=""><a href="/explore">Explorer les projets
</a></li></ul>
</div>
<div class="frequent-items-dropdown-content">
<div data-project-avatar-url="/uploads/-/system/project/avatar/57/Logo.png" data-project-id="57" data-project-name="OSE" data-project-namespace="open-source / OSE" data-project-web-url="/open-source/OSE" data-user-name="zvenigorosky" id="js-projects-dropdown"></div>
</div>
</div>

</div>
</li><li id="nav-groups-dropdown" class="home dropdown header-groups qa-groups-dropdown"><button data-toggle="dropdown" type="button">
Groupes
<svg class=" caret-down"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-down"></use></svg>
</button>
<div class="dropdown-menu frequent-items-dropdown-menu">
<div class="frequent-items-dropdown-container">
<div class="frequent-items-dropdown-sidebar qa-groups-dropdown-sidebar">
<ul>
<li class=""><a class="qa-your-groups-link" href="/dashboard/groups">Vos groupes
</a></li><li class=""><a href="/explore/groups">Explorer les groupes
</a></li></ul>
</div>
<div class="frequent-items-dropdown-content">
<div data-user-name="zvenigorosky" id="js-groups-dropdown"></div>
</div>
</div>

</div>
</li><li class="d-none d-lg-block d-xl-block"><a class="dashboard-shortcuts-activity" title="Activité" href="/dashboard/activity">Activité
</a></li><li class="d-none d-lg-block d-xl-block"><a class="dashboard-shortcuts-milestones" title="Jalons" href="/dashboard/milestones">Jalons
</a></li><li class="d-none d-lg-block d-xl-block"><a class="dashboard-shortcuts-snippets" title="Extraits de code" href="/dashboard/snippets">Extraits de code
</a></li><li class="header-more dropdown d-lg-none d-xl-none">
<a data-toggle="dropdown" href="#">
Plus
<svg class=" caret-down"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-down"></use></svg>
</a>
<div class="dropdown-menu">
<ul>
<li class=""><a title="Activité" href="/dashboard/activity">Activité
</a></li><li class=""><a class="dashboard-shortcuts-milestones" title="Jalons" href="/dashboard/milestones">Jalons
</a></li><li class=""><a class="dashboard-shortcuts-snippets" title="Extraits de code" href="/dashboard/snippets">Extraits de code
</a></li></ul>
</div>
</li>
<li class="hidden">
<a title="Projets" class="dashboard-shortcuts-projects" href="/dashboard/projects">Projets
</a></li>
<li class="line-separator d-none d-sm-block"></li>
<li class=""><a title="Instance Statistics" aria-label="Instance Statistics" data-toggle="tooltip" data-placement="bottom" data-container="body" href="/-/instance_statistics"><svg class="s18"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#chart"></use></svg>
</a></li></ul>

</div>
<div class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<li class="header-new dropdown">
<a class="header-new-dropdown-toggle has-tooltip qa-new-menu-toggle" title="Nouveau…" ref="tooltip" aria-label="Nouveau…" data-toggle="dropdown" data-placement="bottom" data-container="body" data-display="static" href="/projects/new"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#plus-square"></use></svg>
<svg class=" caret-down"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-down"></use></svg>
</a><div class="dropdown-menu dropdown-menu-right">
<ul>
<li class="dropdown-bold-header">
Ce projet
</li>
<li>
<a href="/open-source/OSE/issues/new">Nouveau ticket</a>
</li>
<li>
<a href="/open-source/OSE/merge_requests/new">Nouvelle demande de fusion</a>
</li>
<li class="header-new-project-snippet">
<a href="/open-source/OSE/snippets/new">Nouvel extrait de code</a>
</li>
<li class="divider"></li>
<li class="dropdown-bold-header">GitLab</li>
<li>
<a class="qa-global-new-project-link" href="/projects/new">Nouveau projet</a>
</li>
<li>
<a href="/groups/new">Nouveau groupe</a>
</li>
<li>
<a href="/snippets/new">Nouvel extrait de code</a>
</li>
</ul>
</div>
</li>

<li class="nav-item d-none d-sm-none d-md-block m-auto">
<div class="search search-form">
<form class="form-inline" action="/search" accept-charset="UTF-8" method="get"><input name="utf8" type="hidden" value="&#x2713;" /><div class="search-input-container">
<div class="search-input-wrap">
<div class="dropdown" data-url="/search/autocomplete">
<input type="search" name="search" id="search" placeholder="Search or jump to…" class="search-input dropdown-menu-toggle no-outline js-search-dashboard-options" spellcheck="false" tabindex="1" autocomplete="off" data-issues-path="/dashboard/issues" data-mr-path="/dashboard/merge_requests" aria-label="Search or jump to…" />
<button class="hidden js-dropdown-search-toggle" data-toggle="dropdown" type="button"></button>
<div class="dropdown-menu dropdown-select">
<div class="dropdown-content"><ul>
<li class="dropdown-menu-empty-item">
<a>
Chargement…
</a>
</li>
</ul>
</div><div class="dropdown-loading"><i aria-hidden="true" data-hidden="true" class="fa fa-spinner fa-spin"></i></div>
</div>
<svg class="s16 search-icon"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#search"></use></svg>
<svg class="s16 clear-icon js-clear-input"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#close"></use></svg>
</div>
</div>
</div>
<input type="hidden" name="group_id" id="group_id" class="js-search-group-options" />
<input type="hidden" name="project_id" id="search_project_id" value="57" class="js-search-project-options" data-project-path="OSE" data-name="OSE" data-issues-path="/open-source/OSE/issues" data-mr-path="/open-source/OSE/merge_requests" data-issues-disabled="false" />
<input type="hidden" name="search_code" id="search_code" value="true" />
<input type="hidden" name="repository_ref" id="repository_ref" value="master" />

<div class="search-autocomplete-opts hide" data-autocomplete-path="/search/autocomplete" data-autocomplete-project-id="57" data-autocomplete-project-ref="master"></div>
</form></div>

</li>
<li class="nav-item d-inline-block d-sm-none d-md-none">
<a title="Rechercher" aria-label="Rechercher" data-toggle="tooltip" data-placement="bottom" data-container="body" href="/search?project_id=57"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#search"></use></svg>
</a></li>
<li class="user-counter"><a title="Tickets" class="dashboard-shortcuts-issues" aria-label="Tickets" data-toggle="tooltip" data-placement="bottom" data-container="body" href="/dashboard/issues?assignee_id=15"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#issues"></use></svg>
<span class="badge badge-pill hidden issues-count">
0
</span>
</a></li><li class="user-counter"><a title="Demandes de fusion" class="dashboard-shortcuts-merge_requests" aria-label="Demandes de fusion" data-toggle="tooltip" data-placement="bottom" data-container="body" href="/dashboard/merge_requests?assignee_id=15"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#git-merge"></use></svg>
<span class="badge badge-pill hidden merge-requests-count">
0
</span>
</a></li><li class="user-counter"><a title="À faire" aria-label="À faire" class="shortcuts-todos" data-toggle="tooltip" data-placement="bottom" data-container="body" href="/dashboard/todos"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#todo-done"></use></svg>
<span class="badge badge-pill hidden todos-count">
0
</span>
</a></li><li class="nav-item header-user dropdown">
<a class="header-user-dropdown-toggle" data-toggle="dropdown" href="/zvenigorosky"><img width="23" height="23" class="header-user-avatar qa-user-avatar lazy" data-src="https://secure.gravatar.com/avatar/fe025d6df04d6371a542d7aa33eb409d?s=46&amp;d=identicon" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" />
<svg class=" caret-down"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-down"></use></svg>
</a><div class="dropdown-menu dropdown-menu-right">
<ul>
<li class="current-user">
<div class="user-name bold">
Alexandre Zvenigorosky
</div>
@zvenigorosky
</li>
<li class="divider"></li>
<li>
<a class="profile-link" data-user="zvenigorosky" href="/zvenigorosky">Profil</a>
</li>
<li>
<a href="/profile">Paramètres</a>
</li>
<li>
<a href="/help">Aide</a>
</li>
<li class="divider"></li>
<li>
<a target="_blank" class="text-nowrap" href="https://about.gitlab.com/contributing">Contribuer à GitLab
<svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#external-link"></use></svg>
</a></li>
<li class="divider"></li>

<li>
<a class="sign-out-link" href="/users/sign_out">Se déconnecter</a>
</li>
</ul>

</div>
</li>
</ul>
</div>
<button class="navbar-toggler d-block d-sm-none" type="button">
<span class="sr-only">Activer/désactiver la navigation</span>
<svg class="s12 more-icon js-navbar-toggle-right"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#ellipsis_h"></use></svg>
<svg class="s12 close-icon js-navbar-toggle-left"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#close"></use></svg>
</button>
</div>
</div>
</header>

<div class="layout-page page-with-contextual-sidebar">
<div class="nav-sidebar">
<div class="nav-sidebar-inner-scroll">
<div class="context-header">
<a title="OSE" href="/open-source/OSE"><div class="avatar-container s40 project-avatar">
<img alt="OSE" class="avatar s40 avatar-tile lazy" width="40" height="40" data-src="/uploads/-/system/project/avatar/57/Logo.png" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" />
</div>
<div class="sidebar-context-title">
OSE
</div>
</a></div>
<ul class="sidebar-top-level-items">
<li class="home"><a class="shortcuts-project" href="/open-source/OSE"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#home"></use></svg>
</div>
<span class="nav-item-name">
Projet
</span>
</a><ul class="sidebar-sub-level-items">
<li class="fly-out-top-item"><a href="/open-source/OSE"><strong class="fly-out-top-item-name">
Projet
</strong>
</a></li><li class="divider fly-out-top-item"></li>
<li class=""><a title="Détails du projet" class="shortcuts-project" href="/open-source/OSE"><span>Détails</span>
</a></li><li class=""><a title="Activité" class="shortcuts-project-activity" href="/open-source/OSE/activity"><span>Activité</span>
</a></li>
<li class=""><a title="Analyse de cycle" class="shortcuts-project-cycle-analytics" href="/open-source/OSE/cycle_analytics"><span>Analyse de cycle</span>
</a></li></ul>
</li><li class="active"><a class="shortcuts-tree" href="/open-source/OSE/tree/master"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#doc-text"></use></svg>
</div>
<span class="nav-item-name">
Dépôt
</span>
</a><ul class="sidebar-sub-level-items">
<li class="fly-out-top-item active"><a href="/open-source/OSE/tree/master"><strong class="fly-out-top-item-name">
Dépôt
</strong>
</a></li><li class="divider fly-out-top-item"></li>
<li class="active"><a href="/open-source/OSE/tree/master">Fichiers
</a></li><li class=""><a href="/open-source/OSE/commits/master">Commits
</a></li><li class=""><a href="/open-source/OSE/branches">Branches
</a></li><li class=""><a href="/open-source/OSE/tags">Étiquettes
</a></li><li class=""><a href="/open-source/OSE/graphs/master">Contributeurs
</a></li><li class=""><a href="/open-source/OSE/network/master">Graphique
</a></li><li class=""><a href="/open-source/OSE/compare?from=master&amp;to=master">Comparer
</a></li><li class=""><a href="/open-source/OSE/graphs/master/charts">Statistiques
</a></li>
</ul>
</li><li class=""><a class="shortcuts-issues" href="/open-source/OSE/issues"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#issues"></use></svg>
</div>
<span class="nav-item-name">
Tickets
</span>
<span class="badge badge-pill count issue_counter">
0
</span>
</a><ul class="sidebar-sub-level-items">
<li class="fly-out-top-item"><a href="/open-source/OSE/issues"><strong class="fly-out-top-item-name">
Tickets
</strong>
<span class="badge badge-pill count issue_counter fly-out-badge">
0
</span>
</a></li><li class="divider fly-out-top-item"></li>
<li class=""><a title="Tickets" href="/open-source/OSE/issues"><span>
Liste
</span>
</a></li><li class=""><a title="Tableau" href="/open-source/OSE/boards"><span>
Tableau
</span>
</a></li><li class=""><a title="Étiquettes" href="/open-source/OSE/labels"><span>
Étiquettes
</span>
</a></li>
<li class=""><a title="Jalons" class="qa-milestones-link" href="/open-source/OSE/milestones"><span>
Jalons
</span>
</a></li></ul>
</li><li class=""><a class="shortcuts-merge_requests" href="/open-source/OSE/merge_requests"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#git-merge"></use></svg>
</div>
<span class="nav-item-name">
Demandes de fusion
</span>
<span class="badge badge-pill count merge_counter js-merge-counter">
1
</span>
</a><ul class="sidebar-sub-level-items is-fly-out-only">
<li class="fly-out-top-item"><a href="/open-source/OSE/merge_requests"><strong class="fly-out-top-item-name">
Demandes de fusion
</strong>
<span class="badge badge-pill count merge_counter js-merge-counter fly-out-badge">
1
</span>
</a></li></ul>
</li><li class=""><a class="shortcuts-pipelines" href="/open-source/OSE/pipelines"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#rocket"></use></svg>
</div>
<span class="nav-item-name">
Intégration et livraison continues
</span>
</a><ul class="sidebar-sub-level-items">
<li class="fly-out-top-item"><a href="/open-source/OSE/pipelines"><strong class="fly-out-top-item-name">
Intégration et livraison continues
</strong>
</a></li><li class="divider fly-out-top-item"></li>
<li class=""><a title="Pipelines" class="shortcuts-pipelines" href="/open-source/OSE/pipelines"><span>
Pipelines
</span>
</a></li><li class=""><a title="Tâches" class="shortcuts-builds" href="/open-source/OSE/-/jobs"><span>
Tâches
</span>
</a></li><li class=""><a title="Planifications" class="shortcuts-builds" href="/open-source/OSE/pipeline_schedules"><span>
Planifications
</span>
</a></li><li class=""><a title="Statistiques" class="shortcuts-pipelines-charts" href="/open-source/OSE/pipelines/charts"><span>
Statistiques
</span>
</a></li></ul>
</li><li class=""><a class="shortcuts-operations" href="/open-source/OSE/environments/metrics"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#cloud-gear"></use></svg>
</div>
<span class="nav-item-name">
Opérations
</span>
</a><ul class="sidebar-sub-level-items">
<li class="fly-out-top-item"><a href="/open-source/OSE/environments/metrics"><strong class="fly-out-top-item-name">
Opérations
</strong>
</a></li><li class="divider fly-out-top-item"></li>
<li class=""><a title="Métriques" class="shortcuts-metrics" href="/open-source/OSE/environments/metrics"><span>
Métriques
</span>
</a></li><li class=""><a title="Environnements" class="shortcuts-environments" href="/open-source/OSE/environments"><span>
Environnements
</span>
</a></li></ul>
</li><li class=""><a class="shortcuts-wiki" href="/open-source/OSE/wikis/home"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#book"></use></svg>
</div>
<span class="nav-item-name">
Wiki
</span>
</a><ul class="sidebar-sub-level-items is-fly-out-only">
<li class="fly-out-top-item"><a href="/open-source/OSE/wikis/home"><strong class="fly-out-top-item-name">
Wiki
</strong>
</a></li></ul>
</li><li class=""><a class="shortcuts-snippets" href="/open-source/OSE/snippets"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#snippet"></use></svg>
</div>
<span class="nav-item-name">
Extraits de code
</span>
</a><ul class="sidebar-sub-level-items is-fly-out-only">
<li class="fly-out-top-item"><a href="/open-source/OSE/snippets"><strong class="fly-out-top-item-name">
Extraits de code
</strong>
</a></li></ul>
</li><li class=""><a title="Membres" class="shortcuts-tree" href="/open-source/OSE/settings/members"><div class="nav-icon-container">
<svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#users"></use></svg>
</div>
<span class="nav-item-name">
Membres
</span>
</a><ul class="sidebar-sub-level-items is-fly-out-only">
<li class="fly-out-top-item"><a href="/open-source/OSE/project_members"><strong class="fly-out-top-item-name">
Membres
</strong>
</a></li></ul>
</li><a class="toggle-sidebar-button js-toggle-sidebar" role="button" title="Toggle sidebar" type="button">
<svg class=" icon-angle-double-left"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-double-left"></use></svg>
<svg class=" icon-angle-double-right"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-double-right"></use></svg>
<span class="collapse-text">Collapse sidebar</span>
</a>
<button name="button" type="button" class="close-nav-button"><svg class="s16"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#close"></use></svg>
<span class="collapse-text">Close sidebar</span>
</button>
<li class="hidden">
<a title="Activité" class="shortcuts-project-activity" href="/open-source/OSE/activity"><span>
Activité
</span>
</a></li>
<li class="hidden">
<a title="Réseau" class="shortcuts-network" href="/open-source/OSE/network/master">Graphique
</a></li>
<li class="hidden">
<a title="Statistiques" class="shortcuts-repository-charts" href="/open-source/OSE/graphs/master/charts">Statistiques
</a></li>
<li class="hidden">
<a class="shortcuts-new-issue" href="/open-source/OSE/issues/new">Créer un nouveau ticket
</a></li>
<li class="hidden">
<a title="Tâches" class="shortcuts-builds" href="/open-source/OSE/-/jobs">Tâches
</a></li>
<li class="hidden">
<a title="Commits" class="shortcuts-commits" href="/open-source/OSE/commits/master">Commits
</a></li>
<li class="hidden">
<a title="Tableaux des tickets" class="shortcuts-issue-boards" href="/open-source/OSE/boards">Tableaux des tickets</a>
</li>
</ul>
</div>
</div>

<div class="content-wrapper">

<div class="mobile-overlay"></div>
<div class="alert-wrapper">




<nav class="breadcrumbs container-fluid container-limited" role="navigation">
<div class="breadcrumbs-container">
<button name="button" type="button" class="toggle-mobile-nav"><span class="sr-only">Ouvrir la barre latérale</span>
<i aria-hidden="true" data-hidden="true" class="fa fa-bars"></i>
</button><div class="breadcrumbs-links js-title-container">
<ul class="list-unstyled breadcrumbs-list js-breadcrumbs-list">
<li><a class="group-path breadcrumb-item-text js-breadcrumb-item-text " href="/open-source">open-source</a><svg class="s8 breadcrumbs-list-angle"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-right"></use></svg></li> <li><a href="/open-source/OSE"><img alt="OSE" class="avatar-tile lazy" width="15" height="15" data-src="/uploads/-/system/project/avatar/57/Logo.png" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" /><span class="breadcrumb-item-text js-breadcrumb-item-text">OSE</span></a><svg class="s8 breadcrumbs-list-angle"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#angle-right"></use></svg></li>

<li>
<h2 class="breadcrumbs-sub-title"><a href="/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php">Repository</a></h2>
</li>
</ul>
</div>

</div>
</nav>

<div class="flash-container flash-container-page">
</div>

</div>
<div class=" ">
<div class="content" id="content-body">
<div class="js-signature-container" data-signatures-path="/open-source/OSE/commits/184b62ca1c55395916e2f2b5d068a6026e0b2e16/signatures"></div>
<div class="container-fluid container-limited">

<div class="tree-holder" id="tree-holder">
<div class="nav-block">
<div class="tree-ref-container">
<div class="tree-ref-holder">
<form class="project-refs-form" action="/open-source/OSE/refs/switch" accept-charset="UTF-8" method="get"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="destination" id="destination" value="blob" />
<input type="hidden" name="path" id="path" value="module/Application/src/Application/Controller/ModulateurController.php" />
<div class="dropdown">
<button class="dropdown-menu-toggle js-project-refs-dropdown qa-branches-select" type="button" data-toggle="dropdown" data-selected="master" data-ref="master" data-refs-url="/open-source/OSE/refs?sort=updated_desc" data-field-name="ref" data-submit-form-on-click="true" data-visit="true"><span class="dropdown-toggle-text ">master</span><i aria-hidden="true" data-hidden="true" class="fa fa-chevron-down"></i></button>
<div class="dropdown-menu dropdown-menu-paging dropdown-menu-selectable git-revision-dropdown qa-branches-dropdown">
<div class="dropdown-page-one">
<div class="dropdown-title"><span>Changer de branche ou d’étiquette</span><button class="dropdown-title-button dropdown-menu-close" aria-label="Close" type="button"><i aria-hidden="true" data-hidden="true" class="fa fa-times dropdown-menu-close-icon"></i></button></div>
<div class="dropdown-input"><input type="search" id="" class="dropdown-input-field" placeholder="Rechercher dans les branches et les étiquettes" autocomplete="off" /><i aria-hidden="true" data-hidden="true" class="fa fa-search dropdown-input-search"></i><i role="button" aria-hidden="true" data-hidden="true" class="fa fa-times dropdown-input-clear js-dropdown-input-clear"></i></div>
<div class="dropdown-content"></div>
<div class="dropdown-loading"><i aria-hidden="true" data-hidden="true" class="fa fa-spinner fa-spin"></i></div>
</div>
</div>
</div>
</form>
</div>
<ul class="breadcrumb repo-breadcrumb">
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master">OSE
</a></li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master/module">module</a>
</li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master/module/Application">Application</a>
</li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master/module/Application/src">src</a>
</li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master/module/Application/src/Application">Application</a>
</li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/tree/master/module/Application/src/Application/Controller">Controller</a>
</li>
<li class="breadcrumb-item">
<a href="/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php"><strong>ModulateurController.php</strong>
</a></li>
</ul>
</div>
<div class="tree-controls">
<a class="btn shortcuts-find-file" rel="nofollow" href="/open-source/OSE/find_file/master"><i aria-hidden="true" data-hidden="true" class="fa fa-search"></i>
<span>Rechercher un fichier</span>
</a>
<div class="btn-group" role="group"><a class="btn js-blob-blame-link" href="/open-source/OSE/blame/master/module/Application/src/Application/Controller/ModulateurController.php">Blame</a><a class="btn" href="/open-source/OSE/commits/master/module/Application/src/Application/Controller/ModulateurController.php">History</a><a class="btn js-data-file-blob-permalink-url" href="/open-source/OSE/blob/884f3d8b92fdf8bebc0d29be4778d784fced015a/module/Application/src/Application/Controller/ModulateurController.php">Permalink</a></div>
</div>
</div>

<div class="info-well d-none d-sm-block">
<div class="well-segment">
<ul class="blob-commit-info">
<li class="commit flex-row js-toggle-container" id="commit-184b62ca">
<div class="avatar-cell d-none d-sm-block">
<a href="/zvenigorosky"><img alt="Alexandre Zvenigorosky&#39;s avatar" src="https://secure.gravatar.com/avatar/fe025d6df04d6371a542d7aa33eb409d?s=72&amp;d=identicon" class="avatar s36 d-none d-sm-inline" title="Alexandre Zvenigorosky" /></a>
</div>
<div class="commit-detail flex-list">
<div class="commit-content qa-commit-content">
<a class="commit-row-message item-title" href="/open-source/OSE/commit/184b62ca1c55395916e2f2b5d068a6026e0b2e16">	modified:   module/Application/config/modulateur.config.php</a>
<span class="commit-row-message d-block d-sm-none">
&middot;
184b62ca
</span>
<button class="text-expander js-toggle-button">
<svg class="s12"><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#ellipsis_h"></use></svg>
</button>
<div class="commiter">
<a class="commit-author-link" href="/zvenigorosky">Alexandre Zvenigorosky</a> a créé <time class="js-timeago" title="juin 6, 2018 5:52pm" datetime="2018-06-06T15:52:33Z" data-toggle="tooltip" data-placement="bottom" data-container="body">juin 06, 2018</time>
</div>
<pre class="commit-row-description js-toggle-content append-bottom-8">	modified:   module/Application/src/Application/Controller/ModulateurController.php&#x000A;	modified:   module/Application/src/Application/Entity/Db/Mapping/Application.Entity.Db.TypeModulateurStructure.dcm.xml&#x000A;	modified:   module/Application/src/Application/Entity/Db/TypeModulateurStructure.php&#x000A;	new file:   module/Application/src/Application/Form/Modulateur/Traits/TypeModulateurStructureSaisieFormAwareTrait.php&#x000A;	new file:   module/Application/src/Application/Form/Modulateur/TypeModulateurStructureSaisieForm.php&#x000A;	modified:   module/Application/src/Application/Service/TypeModulateurService.php&#x000A;	modified:   module/Application/view/application/modulateur/index.phtml&#x000A;	new file:   module/Application/view/application/modulateur/type-modulateur-structure-saisie.phtml</pre>
</div>
<div class="commit-actions flex-row d-none d-sm-flex">

<div class="js-commit-pipeline-status" data-endpoint="/open-source/OSE/commit/184b62ca1c55395916e2f2b5d068a6026e0b2e16/pipelines?ref=master"></div>
<div class="commit-sha-group">
<div class="label label-monospace">
184b62ca
</div>
<button class="btn btn btn-default" data-toggle="tooltip" data-placement="bottom" data-container="body" data-title="Copier le condensat SHA du commit" data-class="btn btn-default" data-clipboard-text="184b62ca1c55395916e2f2b5d068a6026e0b2e16" type="button" title="Copier le condensat SHA du commit" aria-label="Copier le condensat SHA du commit"><svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#duplicate"></use></svg></button>

</div>
</div>
</div>
</li>

</ul>
</div>


</div>
<div class="blob-content-holder" id="blob-content-holder">
<article class="file-holder">
<div class="js-file-title file-title-flex-parent">
<div class="file-header-content">
<i aria-hidden="true" data-hidden="true" class="fa fa-file-text-o fa-fw"></i>
<strong class="file-title-name">
ModulateurController.php
</strong>
<button class="btn btn-clipboard btn-transparent prepend-left-5" data-toggle="tooltip" data-placement="bottom" data-container="body" data-class="btn-clipboard btn-transparent prepend-left-5" data-title="Copy file path to clipboard" data-clipboard-text="{&quot;text&quot;:&quot;module/Application/src/Application/Controller/ModulateurController.php&quot;,&quot;gfm&quot;:&quot;`module/Application/src/Application/Controller/ModulateurController.php`&quot;}" type="button" title="Copy file path to clipboard" aria-label="Copy file path to clipboard"><svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#duplicate"></use></svg></button>
<small>
7,2 ko
</small>
</div>

<div class="file-actions">

<div class="btn-group" role="group"><button class="btn btn btn-sm js-copy-blob-source-btn" data-toggle="tooltip" data-placement="bottom" data-container="body" data-class="btn btn-sm js-copy-blob-source-btn" data-title="Copy source to clipboard" data-clipboard-target=".blob-content[data-blob-id=&#39;b2d800cf3cce800c4dd5c79cb07a36802b327773&#39;]" type="button" title="Copy source to clipboard" aria-label="Copy source to clipboard"><svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#duplicate"></use></svg></button><a class="btn btn-sm has-tooltip" target="_blank" rel="noopener noreferrer" title="Open raw" data-container="body" href="/open-source/OSE/raw/master/module/Application/src/Application/Controller/ModulateurController.php"><i aria-hidden="true" data-hidden="true" class="fa fa-file-code-o"></i></a><a download="module/Application/src/Application/Controller/ModulateurController.php" class="btn btn-sm has-tooltip" target="_blank" rel="noopener noreferrer" title="Download" data-container="body" href="/open-source/OSE/raw/master/module/Application/src/Application/Controller/ModulateurController.php?inline=false"><svg><use xlink:href="/assets/icons-8887803ae40f1ee57a8952b5e3b080213a686e327d4d971d8e532c862b79990b.svg#download"></use></svg></a></div>
<div class="btn-group" role="group"><a class="btn js-edit-blob  btn-sm" href="/open-source/OSE/edit/master/module/Application/src/Application/Controller/ModulateurController.php">Éditer</a><a class="btn btn-default btn-sm" href="/-/ide/project/open-source/OSE/edit/master/-/module/Application/src/Application/Controller/ModulateurController.php">EDI Web</a><button name="button" type="submit" class="btn btn-default" data-target="#modal-upload-blob" data-toggle="modal">Replace</button><button name="button" type="submit" class="btn btn-remove" data-target="#modal-remove-blob" data-toggle="modal">Delete</button></div>
</div>
</div>
<div class="js-file-fork-suggestion-section file-fork-suggestion hidden">
<span class="file-fork-suggestion-note">
You're not allowed to
<span class="js-file-fork-suggestion-section-action">
edit
</span>
files in this project directly. Please fork this project,
make your changes there, and submit a merge request.
</span>
<a class="js-fork-suggestion-button btn btn-grouped btn-inverted btn-new" rel="nofollow" data-method="post" href="/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php">Fork</a>
<button class="js-cancel-fork-suggestion-button btn btn-grouped" type="button">
Cancel
</button>
</div>



<div class="blob-viewer" data-type="simple" data-url="/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php?format=json&amp;viewer=simple">
<div class="text-center prepend-top-default append-bottom-default">
<i aria-hidden="true" aria-label="Loading content…" class="fa fa-spinner fa-spin fa-2x"></i>
</div>

</div>


</article>
</div>

<div class="modal" id="modal-remove-blob">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h3 class="page-title">Delete ModulateurController.php</h3>
<button aria-label="Fermer" class="close" data-dismiss="modal" type="button">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form class="js-delete-blob-form js-quick-submit js-requires-input" action="/open-source/OSE/blob/master/module/Application/src/Application/Controller/ModulateurController.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="delete" /><input type="hidden" name="authenticity_token" value="bfCHm8lEvAk9euZxbHacy4Wfk35F6Z7luRLOk/NiRFGLmsXiXf8pkcHJEGEn/pmIfXKOnnbBpBrUTWWJFajaOw==" /><div class="form-group row commit_message-group">
<label class="col-form-label col-sm-2" for="commit_message-1891897456f8bed5624ae29f0886d961">Message de commit
</label><div class="col-sm-10">
<div class="commit-message-container">
<div class="max-width-marker"></div>
<textarea name="commit_message" id="commit_message-1891897456f8bed5624ae29f0886d961" class="form-control js-commit-message" placeholder="Delete ModulateurController.php" required="required" rows="3">
Delete ModulateurController.php</textarea>
</div>
</div>
</div>

<div class="form-group row branch">
<label class="col-form-label col-sm-2" for="branch_name">Branche cible</label>
<div class="col-sm-10">
<input type="text" name="branch_name" id="branch_name" value="patch-1" required="required" class="form-control js-branch-name ref-name" />
<div class="js-create-merge-request-container">
<div class="form-check prepend-top-8">
<input type="checkbox" name="create_merge_request" id="create_merge_request-71a3f7f8c1a6bb0ab40b586368f25c6e" value="1" class="js-create-merge-request form-check-input" checked="checked" />
<label class="form-check-label" for="create_merge_request-71a3f7f8c1a6bb0ab40b586368f25c6e">Créer une <strong>nouvelle demande de fusion</strong> avec ces changements
</label></div>

</div>
</div>
</div>
<input type="hidden" name="original_branch" id="original_branch" value="master" class="js-original-branch" />

<div class="form-group row">
<div class="offset-sm-2 col-sm-10">
<button name="button" type="submit" class="btn btn-remove btn-remove-file">Delete file</button>
<a class="btn btn-cancel" data-dismiss="modal" href="#">Cancel</a>
</div>
</div>
</form></div>
</div>
</div>
</div>

<div class="modal" id="modal-upload-blob">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h3 class="page-title">Replace ModulateurController.php</h3>
<button aria-label="Fermer" class="close" data-dismiss="modal" type="button">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form class="js-quick-submit js-upload-blob-form" data-method="put" action="/open-source/OSE/update/master/module/Application/src/Application/Controller/ModulateurController.php" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><input type="hidden" name="_method" value="put" /><input type="hidden" name="authenticity_token" value="4wZLK8/KJu5T6g0PNpYiUvbD4UyVQSQK/2PCxuEPUqYFbAlSW3Gzdq9Z+x99HicRDi78rKZpHvWSPGncB8XMzA==" /><div class="dropzone">
<div class="dropzone-previews blob-upload-dropzone-previews">
<p class="dz-message light">
Attachez un fichier par glisser‐déposer ou <a class="markdown-selector" href="#">Cliquez pour envoyer</a>
</p>
</div>
</div>
<br>
<div class="dropzone-alerts alert alert-danger data" style="display:none"></div>
<div class="form-group row commit_message-group">
<label class="col-form-label col-sm-2" for="commit_message-44bc2b0278e8375d06848b5feee65997">Message de commit
</label><div class="col-sm-10">
<div class="commit-message-container">
<div class="max-width-marker"></div>
<textarea name="commit_message" id="commit_message-44bc2b0278e8375d06848b5feee65997" class="form-control js-commit-message" placeholder="Replace ModulateurController.php" required="required" rows="3">
Replace ModulateurController.php</textarea>
</div>
</div>
</div>

<div class="form-group row branch">
<label class="col-form-label col-sm-2" for="branch_name">Branche cible</label>
<div class="col-sm-10">
<input type="text" name="branch_name" id="branch_name" value="patch-1" required="required" class="form-control js-branch-name ref-name" />
<div class="js-create-merge-request-container">
<div class="form-check prepend-top-8">
<input type="checkbox" name="create_merge_request" id="create_merge_request-13ebddb8bdf9708b867dca20c839d902" value="1" class="js-create-merge-request form-check-input" checked="checked" />
<label class="form-check-label" for="create_merge_request-13ebddb8bdf9708b867dca20c839d902">Créer une <strong>nouvelle demande de fusion</strong> avec ces changements
</label></div>

</div>
</div>
</div>
<input type="hidden" name="original_branch" id="original_branch" value="master" class="js-original-branch" />

<div class="form-actions">
<button name="button" type="button" class="btn btn-create btn-upload-file" id="submit-all"><i aria-hidden="true" data-hidden="true" class="fa fa-spin fa-spinner js-loading-icon hidden"></i>
Replace file
</button><a class="btn btn-cancel" data-dismiss="modal" href="#">Annuler</a>

</div>
</form></div>
</div>
</div>
</div>

</div>
</div>

</div>
</div>
</div>
</div>


</body>
</html>

