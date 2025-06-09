import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import '../css/app.css';

const app = createApp(App).use(router).mount('#app');
