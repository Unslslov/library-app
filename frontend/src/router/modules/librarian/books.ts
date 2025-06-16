import ManageBook from '../../../views/librarian/ManageBook.vue';
import ManageBooks from '../../../views/librarian/ManageBooks.vue';
import BookFormEdit from '../../../components/books/librarian/BookFormEdit.vue';
import BookFormCreate from '../../../components/books/librarian/BookFormCreate.vue';

import type { RouteRecordRaw } from 'vue-router';

const bookLibrarianRoutes: RouteRecordRaw[] = [
  { 
    path: '/librarian/books', 
    name: 'librarian.book.index', 
    component: ManageBooks,
  },
  { 
    path: '/librarian/books/:id', 
    name: 'librarian.book.show', 
    component: ManageBook,
  },
  { 
    path: '/librarian/books/:id/edit', 
    name: 'librarian.book.edit', 
    component: BookFormEdit,
  },
  { 
    path: '/librarian/books/create', 
    name: 'librarian.book.create', 
    component: BookFormCreate,
  },
];

export default bookLibrarianRoutes;