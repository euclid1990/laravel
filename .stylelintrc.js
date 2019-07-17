module.exports = {
  'extends': 'stylelint-config-standard',
  'plugins': [
    'stylelint-scss'
  ],
  'rules': {
    'indentation': 2,
    'at-rule-empty-line-before': null,
    'unit-no-unknown': [ true, { 'ignoreUnits': ['x'] }],
    'no-empty-source': null,
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true
  }
};
