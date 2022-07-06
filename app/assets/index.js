import React from 'react';
import ReactDOM from 'react-dom/client';
import './styles/scss/main.scss';
import App from './App';
import { BrowserRouter } from 'react-router-dom';
import AuthProvider from './components/auth/AuthProvider';

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
   <BrowserRouter>
      <AuthProvider>
         <App />
      </AuthProvider>
   </BrowserRouter>
);
