import React, { useEffect, useRef } from 'react';
import Search from './search/Search';
import DrinksBox from './drinks/DrinksBox';
import { useState } from 'react';
import axios from 'axios';

const Box = () => {
   const [categories, setCategories] = useState([
      {
         id: 1,
         name: 'słodki',
         color: 'yellow',
         active: false,
      },
      {
         id: 2,
         name: 'kwaśny',
         color: 'green',
         active: false,
      },
      {
         id: 3,
         name: 'orzeźwiający',
         color: 'blue',
         active: false,
      },
      {
         id: 4,
         name: 'lekki',
         color: 'turquoise',
         active: false,
      },
      {
         id: 5,
         name: 'mocny',
         color: 'red',
         active: false,
      },
   ]);
   const [products, setProducts] = useState([]); // selected products
   const [drinks, setDrinks] = useState([]);
   const [sortFunc, setSortFunc] = useState(sortByRelevance);
   const [isLoaded, setIsLoaded] = useState(false);
   const drinksTotalItemsRef = useRef(0);
   const paginationRef = useRef({
      page: 1,
      pagnination: 'server',
      active: true,
   });

   useEffect(() => {
      setIsLoaded(false);
      setPagination(products, categories);
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
   }, [products, categories]);

   const setPagination = (products, categories) => {
      paginationRef.current.active = true;
      paginationRef.current.pagnination = 'server';

      let activeCategories = 0;

      categories.forEach((category) => category.active && activeCategories++);

      if (
         (categories.length != activeCategories && activeCategories > 0) ||
         products.length > 0
      ) {
         paginationRef.current.active = false;
         paginationRef.current.pagnination = 'client';
      }

      console.log(paginationRef.current);
   };

   const getUri = (products, categories) => {
      const pagination = paginationRef.current.active;
      const page =
         paginationRef.current.pagination == 'server'
            ? paginationRef.current.page
            : 1;
      let uri = `/api/drinks?page=${page}&pagination=${pagination}&`;

      products.forEach((product) => {
         uri += `products[]=${product.name}&`;
      });

      categories.forEach((category) => {
         category.active && (uri += `categories[]=${category.name}&`);
      });

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
         <Search
            products={products}
            setProducts={setProducts}
            categories={categories}
            setCategories={setCategories}
         />
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
