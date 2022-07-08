import React, { useContext } from 'react';
import { Routes, Route } from 'react-router-dom';
import DrinksPage from './components/drinks/DrinksPage';
import LoginPage from './components/auth/login/LoginPage';
import SingUpPage from './components/auth/singup/SingUpPage';
import DashboardPage from './components/auth/dashboard/DashboardPage';
import AuthContext from './components/auth/AuthContext';
import Protected from './components/Protected';
import DrinkPage from './components/drink-page/DrinkPage';

function App() {
   const user = useContext(AuthContext).user;

   return (
      <>
         <Routes>
            <Route path="drinki" element={<DrinksPage />} />
            <Route path="drinki/:drinkId/:name" element={<DrinkPage />} />
            <Route
               path="login"
               element={user ? <DashboardPage /> : <LoginPage />}
            />
            <Route
               path="singup"
               element={user ? <DashboardPage /> : <SingUpPage />}
            />
            <Route
               path="dashboard"
               element={
                  <Protected user={user} path="/login">
                     <DashboardPage />
                  </Protected>
               }
            />
         </Routes>
      </>
   );
}

export default App;
