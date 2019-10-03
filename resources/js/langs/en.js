export default {
  en: {
    error: {
      token_expired: {
        title: 'Access token expired',
        text: 'Your access token had been expired'
      },
      server: {
        500: 'Internal server error',
        text: 'Something wrong with server'
      },
      forbidden: {
        403: 'Client Error',
        text: 'You do not have permission  to access  on this serve'
      },
      not_found: {
        404: 'Client Error',
        text: 'The requested resource could not be found'
      },
      unknown: {
        title: 'Unknown error',
        text: 'Request error'
      }
    },
    button: {
      swal: {
        ok: 'Ok !',
        cancel: 'Cancel !'
      }
    },
    export: {
      export_column: {
        id: 'ID',
        name: 'Name',
        created_at: 'Created At',
        updated_at: 'Updated At'
      },
      file_type: {
        excel: 'excel',
        csv: 'csv'
      },
      separate_char: {
        tab: 'Tab',
        comma: 'Comma',
        semi_colon: 'Semi Colon'
      },
      title: 'Export table imports to csv file',
      choose_file_label: 'Choose an File type',
      choose_separate_label: 'Choose a separate char',
      choose_encoding: 'Choose an type encoding',
      choose_field: 'Choose field',
      export: 'Export',
      file_type_validate: 'File type',
      separate_char_validate: 'Separate char',
      encoding_type_validate: 'Encoding type',
      allow_column_validate: 'Export column'
    }
  }
}
