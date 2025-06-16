import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import clientRoutes from './modules/clients';
import bookLibrarianRoutes from './modules/librarian/books';
import loanRoutes from './modules/librarian/loans';
import reservationLibrarianRoutes from './modules/reservations';
import Books from '../views/client/Books.vue';
import bookClientRoutes from './modules/client/books';
import reservationClientRoutes from './modules/client/reservations';
const routes = [
  { 
    path: '/', 
    name: 'login',
    component: Login,
  },
  {
    path: '/clients',
    name: 'client.index',
    component: () => import('../components/client/Index.vue'),
    meta: { requiresAuth: true, role: 'admin' },
    children: [...clientRoutes],
  },
  {
    path: '/librarian',
    name: 'librarian.dashboard',
    component: () => import('../views/librarian/Dashboard.vue'),
    meta: { requiresAuth: true, role: 'librarian' },
    children: [...bookLibrarianRoutes, ...loanRoutes, ...reservationLibrarianRoutes],
  },
  {
    path: '/books',
    name: 'book.index',
    component: Books,
    meta: { requiresAuth: true, role: 'client' },
    children: [...bookClientRoutes, ...reservationClientRoutes],
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const isAuthenticated = !!token

  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRole = to.meta.role as string | undefined

  const userRole = localStorage.getItem('userRole') 

  if (requiresAuth && !isAuthenticated) {
    next('/')
    return
  }

  if (requiredRole) {
    if (!userRole) {
      next('/')
      return
    }

    if (userRole !== requiredRole) {
      switch (userRole) {
        case 'admin':
          next({ name: 'client.index' })
          break
        case 'librarian':
          next({ name: 'librarian.dashboard' })
          break
        case 'client':
          next({ name: 'book.index' })
          break
        default:
          next('/')
      }
      return
    }
  }

  next()
})

export default router;