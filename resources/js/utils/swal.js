import { $t } from '@/utils/i18n'
import Sweetalert2 from 'sweetalert2';

const base = {
  reverseButtons: true,
  confirmButtonText: $t('button.swal.ok'),
  cancelButtonText: $t('button.swal.cancel')
}

const baseconfig = {
  warning: {
    ...base,
    type: 'warning',

  },
  error: {
    ...base,
    type: 'error',
    
  },
  success: {
    ...base,
    type: 'success',
  }
}

export default function swal(type, config) {
  return Sweetalert2.fire({
    ...baseconfig[type],
    ...config
  })
}
