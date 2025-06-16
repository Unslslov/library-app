import BookDetails from '../../../views/client/BookDetails.vue';

import type { RouteRecordRaw } from 'vue-router';

const bookClientRoutes: RouteRecordRaw[] = [
  { 
    path: '/books/:id', 
    name: 'book.show', 
    component: BookDetails,
  },
];

export default bookClientRoutes;