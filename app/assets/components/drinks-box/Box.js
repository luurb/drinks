import React, { useEffect } from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';
import axios from 'axios';

const Box = () => {
   const [drinks, setDrinks] = useState([]);
   const [sortFunc, setSortFunc] = useState(sortByRelevance);
   const [isLoaded, setIsLoaded] = useState(true);

   const updateDrinks = (products, categories) => {
      setIsLoaded(false);

      let uri = '/api/drinks?';
      products.forEach((product) => {
         uri += `products[]=${product.name}&`;
      });
      categories.forEach((category) => {
         category.active && (uri += `categories[]=${category.name}&`);
      });

      (async () => {
         try {
            const response = await axios.get(uri, {
               headers: { accept: 'application/json' },
            });
            const filteredDrinks = filterDrinks(response.data);
            console.log(filteredDrinks);
            setIsLoaded(true);
            setDrinks(filteredDrinks);
         } catch (error) {
            console.log(error);
            setDrinks([]);
         }
      })();

      const filterDrinks = (data) => {
         return data.map((drink) => {
            drink = filterProducts(drink);
            drink = filterCategories(drink);
            return {
               ...drink,
               revelance: drink.productRelevance + drink.categoryRelevance,
            };
         });
      };

      /*
      Set products revelance and active status for colors
      if drink has a product from selected products increment revelance
      */
      const filterProducts = (drink) => {
         let productRelevance = 0;
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

         return { ...drink, productRelevance: productRelevance };
      };

      /*
      Set categories revelance and replace fetched categories with 
      categories which contain colors from Search component
      */
      const filterCategories = (drink) => {
         let categoryRelevance = 0;
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

         return { ...drink, categoryRelevance: categoryRelevance };
      };
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
            />
      </div>
   );
};

export default Box;
