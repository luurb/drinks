import React, { useEffect, useRef } from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';
import axios from 'axios';

const Box = () => {
   const [drinks, setDrinks] = useState([]);
   const [sortFunc, setSortFunc] = useState(sortByRelevance);
   const [isLoaded, setIsLoaded] = useState(true);
   const drinksTotalItemsRef = useRef(0);

   const updateDrinks = (products, categories) => {
      setIsLoaded(false);

      const uri = getUri(products, categories);

      (async () => {
         try {
            const response = await axios.get(uri);
            const filteredDrinks = filterDrinks(
               response.data['hydra:member'],
               products,
               categories
            );
            drinksTotalItemsRef.current = response.data['hydra:totalItems'];
            console.log(filteredDrinks);
            setIsLoaded(true);
            setDrinks(filteredDrinks);
         } catch (error) {
            console.log(error);
            setDrinks([]);
         }
      })();
   };

   const getUri = (products, categories) => {
      let uri = `/api/drinks?`;
      products.forEach((product) => {
         uri += `products[]=${product.name}&`;
      });

      let activeCategoryCounter = 0;
      categories.forEach((category) => {
         if (category.active) {
            uri += `categories[]=${category.name}&`;
            activeCategoryCounter++;
         }
      });

      let pagination =
         products.length == 0 &&
         categories.some((category) => category.active) == 0
            ? 'true'
            : 'false';

      /* 
      Set pagination to true if all categories are active and 
      no products are selected.
      */
      pagination =
         products.length == 0 && activeCategoryCounter == 5
            ? 'true'
            : pagination;
      uri += `&pagination=${pagination}`;

      return uri;
   };

   const filterDrinks = (data, products, categories) => {
      return data.map((drink) => {
         let productRelevance = 0;
         let categoryRelevance = 0;

         /*
         Set products revelance and active status for colors
         if drink has a product from selected products increment revelance
         */
         drink.products = drink.products.map((product) => {
            if (
               products.some(
                  (selectedProduct) => selectedProduct.name == product.name
               )
            ) {
               productRelevance++;
               return { ...product, active: true };
            } else {
               return { ...product, active: false };
            }
         });

         /*
         Set categories revelance and replace fetched categories with 
         categories which contain colors from Search component
         */
         drink.categories = drink.categories.map((category) => {
            categories.some(
               (selectedCategory) =>
                  selectedCategory.name == category.name &&
                  selectedCategory.active
            ) && categoryRelevance++;

            return categories.find(
               (selectedCategory) => selectedCategory.name == category.name
            );
         });

         return {
            ...drink,
            productRelevance: productRelevance,
            categoryRelevance: categoryRelevance,
            revelance: drink.productRelevance + drink.categoryRelevance,
         };
      });
   };

   const setSortFuncBySelectedOption = (sortOption) => {
      const callback = (() => {
         switch (sortOption.name) {
            case 'trafność':
               return sortByRelevance;
               break;
            default:
               return sortByRelevance;
         }
      })();

      setSortFunc(() => callback);
   };

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
      <div className="drinks-box__box">
         <Search setDrinks={updateDrinks} />
         <DrinksBox
            drinks={drinks.sort((a, b) => sortFunc(a, b))}
            setSortFuncBySelectedOption={setSortFuncBySelectedOption}
            isLoaded={isLoaded}
            drinksTotalItems={drinksTotalItemsRef.current}
         />
      </div>
   );
};

export default Box;
