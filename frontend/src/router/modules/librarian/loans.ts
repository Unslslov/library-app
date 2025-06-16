import LoanForm from '../../../components/books/librarian/LoanForm.vue';
import ManageLoans from '../../../views/librarian/ManageLoans.vue';
import type { RouteRecordRaw } from 'vue-router';

const loanRoutes: RouteRecordRaw[] = [
  { 
    path: '/librarian/loans',
    name: 'librarian.loans.index',
    component: ManageLoans,
  },
  { 
    path: '/librarian/loans/create', 
    name: 'librarian.loan.create', 
    component: LoanForm,
  },
];

export default loanRoutes;