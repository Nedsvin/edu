import { defineStore } from 'pinia'

const SESSION_DURATION_MS = 60 * 60 * 1000 // 1 hora

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user') || 'null') as {
      id: number
      name: string
      role: string
    } | null,
    token: localStorage.getItem('token') || '',
    loginTimestamp: Number(localStorage.getItem('loginTimestamp')) || null,
  }),

  actions: {
    login(user: { id: number; name: string; role: string }, token: string) {
      this.user = user
      this.token = token
      this.loginTimestamp = Date.now()

      localStorage.setItem('user', JSON.stringify(user))
      localStorage.setItem('token', token)
      localStorage.setItem('loginTimestamp', String(this.loginTimestamp))
    },

    logout() {
      this.user = null
      this.token = ''
      this.loginTimestamp = null

      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('loginTimestamp')
    },

    isSessionExpired(): boolean {
      if (!this.loginTimestamp) return true
      return Date.now() - this.loginTimestamp > SESSION_DURATION_MS
    },

    checkAuth() {
      const token = localStorage.getItem('token')
      const timestamp = Number(localStorage.getItem('loginTimestamp'))

      if (!token || !timestamp) return false

      const expired = Date.now() - timestamp > SESSION_DURATION_MS
      return !expired
    },
  },
})
