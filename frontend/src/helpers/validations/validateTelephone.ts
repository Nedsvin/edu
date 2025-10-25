export const validateTelephone = (fone: string): boolean => {
  fone = fone.replace(/[^\d]+/g, '')
  return fone.length >= 10 && fone.length <= 11
}
