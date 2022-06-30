import React, { useEffect, useState, useCallback, useRef } from 'react';
import Drink from './Drink';
import SortBox from './SortBox';
import LoadingDrink from './LoadingDrink';

const DrinksBox = ({
   drinks,
   isLoaded,
   setSortFuncBySelectedOption,
   drinksTotalItems,
}) => {
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
   const observerRef = useRef();

   useEffect(() => {
      setSortFuncBySelectedOption(sortOptions.find((option) => option.active));
   }, [sortOptions]);

   useEffect(() => {
      const node = observerRef.current;
      if (!node) return;

      const options = {
         root: null,
         rootMargin: '0px',
         threshold: 1.0
      }

      const observer = new IntersectionObserver((entries) => {
         entries[0].isIntersecting && console.log('In view');
      }, options);
      observer.observe(node);

   }, [drinks]);

   const drinksCounter = () => {
      switch (drinksTotalItems) {
         case 0:
            return 'Nie znaleziono drinków dla wybranych produktów';
            break;
         case 1:
            return `Znaleziono 1 drink`;
            break;
         case 2:
         case 3:
         case 4:
            return `Znaleziono ${drinksTotalItems} drinki`;
            break;
         default:
            return `Znaleziono ${drinksTotalItems} drinków`;
      }
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
         {isLoaded ? (
            <div className="drinks__wrapper">
               {drinks.map((drink, index) =>
                  index + 1 != drinks.length ? (
                     <Drink key={drink.id} drink={drink} />
                  ) : (
                     <Drink key={drink.id} drink={drink} ref={observerRef} />
                  )
               )}
            </div>
         ) : (
            <LoadingDrink />
         )}
      </div>
   );
};

export default DrinksBox;
