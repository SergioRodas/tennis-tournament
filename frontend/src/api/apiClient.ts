import axios from 'axios';

import { API_URLS } from '@/config/urls';

const apiClient = axios.create({
  baseURL: API_URLS.BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

export default apiClient;
