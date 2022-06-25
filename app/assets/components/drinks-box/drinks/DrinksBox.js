import React from 'react';
import Drink from './Drink';
import SortBox from './SortBox';

const DrinksBox = ({ drinks }) => {
   const drinksCounter = () => {
      const drinksLength = drinks.length;
      switch (drinksLength) {
         case 0:
            return 'Nie znaleziono drinków dla wybranych produktów';
            break;
         case 1:
            return `Znaleziono 1 drink`;
            break;
         case 2:
         case 3: 
         case 4:
            return `Znaleziono ${drinksLength} drinki`;
            break;
         default:
            return `Znaleziono ${drinksLength} drinków`;
      }
   }

   return (
      <div className="drinks">
         <div className="drinks__top">
            <span className="drinks__counter">{drinksCounter()}</span>
            <SortBox />
         </div>
         <div className="drinks__wrapper">
            {drinks.map((drink) => (
               <Drink key={drink.id} drink={drink} />
            ))}
         </div>
      </div>
   );
};

export default DrinksBox;
