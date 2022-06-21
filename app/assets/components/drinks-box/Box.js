import React from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';

const Box = () => {
   const [drinks, setDrinks] = useState([]);
   const drinksArr = [
      {
         id: 1,
         name: 'white russian',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, syrop cukrowy, lód',
         type: 'słodki',
         image: '../images/drinks/mojito.jpg',
      },
      {
         id: 2,
         name: 'Mohito',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'whiskey, lód',
         type: 'orzeźwiający, mocny',
         image: '../images/drinks/mojito.jpg',
      },
      {
         id: 3,
         name: 'Mohito',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, sok z cytryny, lód',
         type: 'słodki, mocny',
         image: '../images/drinks/mojito.jpg',
      },
      {
         id: 4,
         name: 'Mohito',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, sok jabłkowy, lód',
         type: 'mocny',
         image: '../images/drinks/mojito.jpg',
      },
      {
         id: 5,
         name: 'Mohito',
         desc: 'Wielu webmasterów i designerów używa Lorem Ipsum w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie(humorystyczne wstawki itd).',
         products: 'wódka, kawa, syro cukrowy, lód',
         type: 'słodki',
         image: '../images/drinks/mojito.jpg',
      },
   ];

   const updateDrinks = (products, categories) => {
      setDrinks(
         drinksArr.filter(
            (drink) =>
               products.some((product) =>
                  drink.products.includes(product.name)
               ) ||
               categories.some((category) => drink.type.includes(category.name))
         )
      );
   };

   return (
      <div className="drinks-box">
         <Search setDrinks={updateDrinks} />
         <DrinksBox drinks={drinks} />
      </div>
   );
};

export default Box;
