import React from 'react';
import { Routes, Route } from 'react-router-dom';
import DrinksPage from './components/drinks/DrinksPage';
import LoginPage from './components/auth/login/LoginPage';
import SingUpPage from './components/auth/singup/SingUpPage';

function App() {
   return (
      <>
         <Routes>
            <Route path="drinki" element={<DrinksPage />} />
            <Route path="login" element={<LoginPage />} />
            <Route path="singup" element={<SingUpPage />} />
         </Routes>
      </>
   );
}

export default App;
