import React from 'react';
import { Routes, Route } from 'react-router-dom';
import DrinksPage from './components/drinks/DrinksPage';

function App() {
   return (
      <>
         <Routes>
            <Route path="drinki" element={<DrinksPage />} />
         </Routes>
      </>
   );
}

export default App;
