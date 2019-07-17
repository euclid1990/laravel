export default function env(e, d = '') {
  if (typeof process.env[e] === 'undefined' || process.env[e] === '') return d

  return process.env[e]
}
