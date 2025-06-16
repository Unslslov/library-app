import ClientShow from '../../components/client/Show.vue';
import ClientCreate from '../../components/client/Create.vue';
import ClientEdit from '../../components/client/Edit.vue';
import type { RouteRecordRaw } from 'vue-router';

const clientRoutes: RouteRecordRaw[] = [
  { 
    path: '/clients/create', 
    name: 'client.create', 
    component: ClientCreate,
  },
  { 
    path: '/clients/:id', 
    name: 'client.show', 
    component: ClientShow,
  },
  { 
    path: '/clients/:id/edit', 
    name: 'client.edit', 
    component: ClientEdit,
  }
];

export default clientRoutes;