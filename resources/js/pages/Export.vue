<template>
  <div class="row">
    <div class="auth-panel col-12">
      <div class="auth-panel__wrapper col-xl-6 col-md-8 col-10">
        <div class="auth-panel__wrapper__header col-6__header">
          <div class="header">
            {{ $t('export.title') }}
          </div>
        </div>
        <div>
          <form
            class="form container"
            @submit.prevent="validateFormData"
          >
            <div
              v-if="error"
              class="alert alert-danger col-sm-10 offset-sm-1 error"
            >
              {{ errorMessage }}
            </div>
            <div class="form-group row form-row">
              <label
                class="col-4 col-form-label"
              >{{ $t('export.choose_file_label') }}</label>
              <div class="col-6">
                <select
                  v-model="selected_type"
                  v-validate="'required'"
                  class="form-control"
                  name="selected_type"
                  :data-vv-as="$t('export.file_type_validate')"
                >
                  <option
                    v-for="type in fileTypes"
                    :key="type.value"
                    :value="type.value"
                  >
                    {{ $t(type.label) }}
                  </option>
                </select>
              </div>
            </div>
            <template
              v-if="show"
            >
              <div class="form-group row form-row">
                <label
                  class="col-4 col-form-label"
                >{{ $t('export.choose_separate_label') }}</label>
                <div class="col-6">
                  <select
                    v-model="selected_char"
                    v-validate="'required'"
                    class="browser-default custom-select"
                    name="selected_char"
                    :data-vv-as="$t('export.separate_char_validate')"
                  >
                    <option
                      v-for="char in sperateChars"
                      :key="char.value"
                      :value="char.value"
                    >
                      {{ $t(char.label) }}
                    </option>
                  </select>
                </div>
              </div>
            </template>
            <div class="form-group row form-row">
              <label
                class="col-4 col-form-label"
              >{{ $t('export.choose_encoding') }}</label>
              <div class="col-6">
                <select
                  v-model="selected_encoding"
                  v-validate="'required'"
                  class="form-control"
                  name="selected_encoding"
                  :data-vv-as="$t('export.encoding_type_validate')"
                >
                  <option
                    v-for="(value, key) in encodingTypes"
                    :key="key"
                    :value="key"
                  >
                    {{ value }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group row form-row">
              <label
                class="col-4 col-form-label"
              >{{ $t('export.choose_field') }}</label>
              <div class="col-6 checkbox">
                <template
                  v-for="(value, key) in exportColumns"
                >
                  <input
                    :key="key"
                    v-model="checked"
                    v-validate="'required'"
                    class="checkbox-inline"
                    type="checkbox"
                    :value="key"
                    name="checked"
                    :data-vv-as="$t('export.allow_column_validate')"
                  >
                  <label
                    :key="value"
                    class="label-column"
                  >{{ $t(value) }}</label>
                </template>
              </div>
            </div>
            <div class="form-row">
              <div class="offset-4">
                <button
                  type="submit"
                  class="btn btn-primary"
                >
                  {{ $t('export.export') }}
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="footer" />
      </div>
    </div>
  </div>
</template>
<script>
import Request from '../utils/api'

export default {
  data: function() {
    return {
      fileTypes: window.config.configExport.types,
      selected_type: '',
      sperateChars: window.config.configExport.types.csv.separation,
      selected_char: '',
      encodingTypes: window.config.configExport.encoding,
      selected_encoding: '',
      exportColumns: window.config.configExport.export_column,
      checked: [],
      errorMessage: '',
      error: false
    }
  },
  computed: {
    show: function() {
      return window.config.configExport.types.csv.value.includes(this.selected_type)
    }
  },
  methods: {
    validateFormData() {
      this.errorMessage = null
      this.$validator.validateAll().then(result => {
        if (result) {
          this.error = false
          this.sendData()
        } else {
          this.error = true
          this.errorMessage = this.errors.first('selected_type') ||
            this.errors.first('selected_char') ||
            this.errors.first('selected_encoding') ||
            this.errors.first('checked')
        }
      })
    },
    sendData: async function() {
      try {
        const result = await Request.post({
          url: window.endpoint.route('export.create'),
          data: {
            file_type: this.selected_type,
            separation: this.selected_char,
            encoding: this.selected_encoding,
            export_column: this.checked
          },
          responseType: 'json'
        })
        if (result && result.data) {
          return this.download(result)
        }
      } catch (e) {
        this.error = true
        this.errorMessage = e.message
      }
    },
    // Create "a" tag contains virtual url to perform action: browser auto click and download file
    download: function(result) {
      const link = document.createElement('a')
      link.setAttribute('download', result.data.fileName)
      link.setAttribute('href', `data:${result.data.fileMime};base64,${result.data.fileDataBase64}`)
      link.click()
    }
  }
}

</script>
<style lang="scss" scoped>
@import '~sass/app';

.auth-panel {
  display: flex;
  margin-top: 20px;
  justify-content: center;

  &__wrapper {
    @include padding-none();

    border: 1px solid $black;
    border-radius: 7px;
    margin-top: 80px;
    overflow: hidden;

    &__header {
      background: $gray-light;
      padding: 12px 20px;
    }
  }
}

.header {
  font-size: 20px;
}

.checkbox {
  display: inline-block;
}

.label-column {
  padding-right: 15px;
}

.form-row {
  margin-top: 30px;

  &__label {
    @include padding-none();
    margin-left: 10px;
  }
}

.footer {
  margin-top: 30px;
}

.error {
  margin-top: 30px;
}
</style>
