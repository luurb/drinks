import React from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';
import axios from 'axios';

const Box = () => {
   const [drinks, setDrinks] = useState([]);

   const updateDrinks = (products, categories) => {
      let uri = '/api/drinks?';

      products.forEach((product) => {
         uri += `products[]=${product.name}&`;
      });
      categories.forEach((category) => {
         uri += `categories[]=${category.name}&`;
      });

      (async () => {
         try {
            const response = await axios.get(uri, {
               headers: { accept: 'application/json' },
            });
            console.log(response.data);
            setDrinks(response.data);
         } catch (error) {
            console.log(error);
            setDrinks([]);
         }
      })();
   };

   return (
      <div className="drinks-box">
         <Search setDrinks={updateDrinks} />
         <DrinksBox drinks={drinks} />
      </div>
   );
};

export default Box;
