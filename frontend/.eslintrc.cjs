module.exports = {
  root: true,
  env: { browser: true, es2020: true },
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:react-hooks/recommended',
    'plugin:prettier/recommended',
  ],
  ignorePatterns: ['dist', '.eslintrc.cjs'],
  parser: '@typescript-eslint/parser',
  plugins: ['react-refresh', '@typescript-eslint/eslint-plugin', 'import'],
  rules: {
    "react/prop-types": "off",
    'react/jsx-no-target-blank': 'off',
    'react/react-in-jsx-scope': 'off',
    'react-refresh/only-export-components': [
      'warn',
      { allowConstantExport: true },
    ],
    'semi': ['error', 'always'],
    'prettier/prettier': [
      'error',
      {
        endOfLine: 'auto',
      },
    ],
    "import/order": [
      "warn",
      {
        groups: ["builtin", "external", ["sibling", "parent"], "index"],
        pathGroups: [
          {
            pattern: "$app/**",
            group: "external"
          },
          {
            pattern: "~/**",
            group: "sibling"
          }
        ],
        alphabetize: {
          order: "asc",
          caseInsensitive: true
        },
        "newlines-between": "always"
      }
    ],
    '@typescript-eslint/no-explicit-any': 'off',
  },
};
