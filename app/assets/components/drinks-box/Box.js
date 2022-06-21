import React from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';

const Box = () => {
   const [drinks, setDrinks] = useState([
      {
         name: 'white russian',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, syro cukrowy, lód',
         type: 'słodki',
         image: '../images/drinks/mojito.png',
      },
      {
         name: 'Mohito',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, syro cukrowy, lód',
         type: 'słodki',
         image: '../images/drinks/mojito.png',
      },
   ]);

   const updateDrinks = (products, categories) => {
      console.log(products, categories);
   };

   return (
      <div className="drinks-box">
         <Search setDrinks={updateDrinks} />
         <DrinksBox />
      </div>
   );
};

export default Box;
