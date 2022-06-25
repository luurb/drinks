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
            setDrinks(filterDrinks(response.data));
         } catch (error) {
            console.log(error);
            setDrinks([]);
         }
      })();

      const filterDrinks = (data) => {
         const filteredDrinks = data.map((drink) => {
            let productRelevance = 0;
            let categoryRelevance = 0;

            //Set products revelance (if drink has a product from selected products increment revelance)
            drink.products.forEach((product) => {
               products.some(
                  (selectedProduct) => selectedProduct.name == product.name
               ) && productRelevance++;
            });

            //Same as products but with categories
            drink.categories.forEach((category) => {
               categories.some(
                  (selectedCategory) => selectedCategory.name == category.name
               ) && categoryRelevance++;
            });

            return {
               ...drink,
               productRelevance: productRelevance,
               categoryRelevance: categoryRelevance,
               revelance: productRelevance + categoryRelevance,
            };
         });

         console.log('Filtered:', filteredDrinks);
         return filteredDrinks;
      };
   };

   return (
      <div className="drinks-box">
         <Search setDrinks={updateDrinks} />
         <DrinksBox drinks={drinks} />
      </div>
   );
};

export default Box;
