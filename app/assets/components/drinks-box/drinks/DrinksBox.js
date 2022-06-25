import React, { useEffect } from 'react';
import Drink from './Drink';
import SortBox from './SortBox';
import { useState } from 'react';

const DrinksBox = ({ drinks }) => {
   const [toogle, setToogle] = useState(false);
   const [sortOptions, setSortOptions] = useState([
      {
         id: 1,
         name: 'trafność',
         active: true,
      },
      {
         id: 2,
         name: 'ocena',
         active: false,
      },
      {
         id: 3,
         name: 'komentarze',
         active: false,
      },
      {
         id: 4,
         name: 'ocena + składniki',
         active: false,
      },
      {
         id: 5,
         name: 'ocena + kategorie',
         active: false,
      },
   ]);

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
   };

   useEffect(() => {
      const sortOption = sortOptions.find((option) => option.active);
      const callback = (() => {
         switch (sortOption.name) {
            case 'trafność':
               return sortByRelevance;
               break;
            default:
               return sortByRelevance;
         }
      })();
   }, [sortOptions]);

   const sortByRelevance = (firstDrink, secondDrink) => {
      if (firstDrink.revelance > secondDrink.revelance) {
         return -1;
      }
      if (firstDrink.revelance < secondDrink.revelance) {
         return 1;
      }

      return 0;
   };

   return (
      <div className="drinks">
         <div className="drinks__top">
            <span className="drinks__counter">{drinksCounter()}</span>
            <div
               className="drinks__sort-box"
               onClick={() => setToogle(!toogle)}
            >
               <span>Sortuj</span>
               {toogle ? (
                  <i className="fa-solid fa-caret-up sort-caret"></i>
               ) : (
                  <i className="fa-solid fa-caret-down sort-caret"></i>
               )}
               {toogle && (
                  <SortBox
                     sortOptions={sortOptions}
                     setSortOptions={setSortOptions}
                  />
               )}
            </div>
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
