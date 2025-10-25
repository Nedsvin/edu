import api from './axios'
import Swal from 'sweetalert2'

const request = {

  get: async (url: string, params?: object) => {
    return api.get(url, { params })
  },

  post: async (url: string, data?: object) => {
    try {
      const response = await api.post(url, data)

      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      handleRequestError(error)
      throw error
    }
  },

  put: async (url: string, data?: object) => {
    try {
      const response = await api.put(url, data)

      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      handleRequestError(error)
      throw error
    }
  },
  patch: async (url: string, data?: object) => {
    try {
      const response = await api.patch(url, data)

      await checkStatus(response.status, response?.data?.message || '')

      return response
    } catch (error: any) {
      handleRequestError(error)
      throw error
    }
  },

  delete: async (url: string, data?: object) => {
    return api.delete(url, data)
  },
}


const checkStatus = async (statusCode: number, message?: string) => {
  if (statusCode === 401 && message) {
    alertError(message)
    throw new Error(message)
  }
}

const alertError = (message: string) => {
  const formattedMessage = Array.isArray(message)
    ? message.join('<br />')
    : message

  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    html: formattedMessage,
    showConfirmButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#3085d6',
  })
}

const handleRequestError = (error: any) => {
  if (error.response) {
    alertError(error.response.data.error || 'Aconteceu um erro interno inesperado.')
  } else if (error.request) {
    alertError('Erro de conexão.')
  } else {
    alertError(error.error || 'Ocorreu um erro.')
  }
}

export default request
