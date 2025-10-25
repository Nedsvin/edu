import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import LoginTemplate from '@/layouts/LoginTemplate.vue'
import Menu from '@/layouts/Menu.vue'
import LoginPage from '@/views/Login/Login.vue'
import Home from '@/views/Home/Home.vue'
import StudentList from '@/views/Students/List.vue'
import StudentForm from '@/views/Students/Form.vue'
import ClassList from '@/views/Classes/List.vue'
import ClassForm from '@/views/Classes/Form.vue'
import RegistrationsList from '@/views/Registrations/List.vue'
import RegistrationsForm from '@/views/Registrations/Form.vue'
import UserList from '@/views/User/List.vue'
import ERR404 from '@/views/ERR404.vue'

const routes = [
  {
    path: '/docs',
    redirect: '/api/docs',
  },
  {
    path: '/login',
    component: LoginTemplate,
    children: [
      {
        path: '',
        component: LoginPage,
        name: 'Login',
      },
    ],
    redirect: { name: 'Login' },
  },
  {
    path: '/logout',
    name: 'Logout',
    beforeEnter(to, from, next) {
      const authStore = useAuthStore()
      authStore.logout()
      next({ name: 'Login' })
    },
  },
  {
    path: '/',
    component: Menu,
    meta: { requiresAuth: true },
    children: [
      {
        path: '/home',
        component: Home,
        name: 'Home',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos',
        component: StudentList,
        name: 'AlunosList',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos/novo',
        component: StudentForm,
        name: 'AlunosNovo',
        meta: { requiresAuth: true },
      },
      {
        path: 'alunos/editar/:id',
        component: StudentForm,
        name: 'AlunosEditar',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas',
        component: ClassList,
        name: 'TurmasList',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas/novo',
        component: ClassForm,
        name: 'TurmasNovo',
        meta: { requiresAuth: true },
      },
      {
        path: 'turmas/editar/:id',
        component: ClassForm,
        name: 'TurmasEditar',
        meta: { requiresAuth: true },
      },
      {
        path: 'matriculas',
        component: RegistrationsList,
        name: 'RegistrationsList',
        meta: { requiresAuth: true },
      },
      {
        path: 'matriculas/nova',
        component: RegistrationsForm,
        name: 'MatriculasNova',
        meta: { requiresAuth: true },
      },
      {
        path: 'usuarios',
        component: UserList,
        name: 'UserList',
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: ERR404,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = !!authStore.user
  if (to.meta.requiresAuth) {
    if (!isAuthenticated || authStore.isSessionExpired()) {
      authStore.logout()
      next({ name: 'Login' })
      return
    }
  }
  next()
})

export default router
