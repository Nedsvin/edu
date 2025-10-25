import axios from 'axios'

const tiposLogradouros = [
  'Rua',
  'Avenida',
  'Travessa',
  'Alameda',
  'Viela',
  'Praça',
  'Rodovia',
  'Estrada',
  'Largo',
  'Beco',
  'Passagem',
  'Caminho',
  'Servidão',
  'Via',
  'Boulevard',
  'Setor',
  'Quadra',
]
export async function getCepInfo(cep: string) {
  const sanitizedCep = cep.replace(/\D/g, '')
  if (sanitizedCep.length !== 8) {
    throw new Error('CEP inválido. Deve conter 8 dígitos.')
  }
  try {
    const response = await axios.get(
      `https://viacep.com.br/ws/${sanitizedCep}/json/`
    )
    if (response.data.erro) {
      throw new Error('CEP não encontrado.')
    }
    const logradouroCompleto = response.data.logradouro || ''
    const palavrasLogradouro = logradouroCompleto.split(' ')

    let tipoLogradouro = ''
    let endereco = logradouroCompleto

    if (palavrasLogradouro.length > 1) {
      if (tiposLogradouros.includes(palavrasLogradouro[0])) {
        tipoLogradouro = palavrasLogradouro[0]
        endereco = palavrasLogradouro.slice(1).join(' ')
      } else {
        tipoLogradouro = palavrasLogradouro[0]
        endereco = palavrasLogradouro.slice(1).join(' ')
      }
    }
    return {
      ...response.data,
      logradouro: tipoLogradouro,
      endereco,
    }
  } catch (error: any) {
    throw new Error(error.response?.data?.message || 'Erro ao getId o CEP.')
  }
}
