// https://vitejs.dev/config/#build-polyfillmodulepreload
import 'vite/modulepreload-polyfill';

// Styles
//import.meta.glob('./styles/**/*.scss', {eager: true});
import.meta.glob('./styles/**/*.css', {eager: true});

// Scripts JS
import.meta.glob('./scripts/**/*.js', {eager: true});

// Vue
import {createApp} from 'vue';

// First let's load all components that should be available to in-browser template compilation

// Example of how to import **all** components

const modules = import.meta.glob('./components/**/*.vue', {eager: true});

let componentsPath = "./components/";

const components = {}
for (const path in modules) {
    let compName = path.slice(componentsPath.length, -4).replace('/', '');

    components[compName] = modules[path].default
}

// if importing all is too much you can always do it manually
// import HelloWorld from './components/HelloWorld.vue'
// const components = {
//   HelloWorld,
// }

// instantiate the Vue apps
// Note our lookup is a wrapping div with .vue-app class

for (const el of document.getElementsByClassName('vue-app')) {
    createApp({
        template: el.innerHTML, components
    }).mount(el)
}