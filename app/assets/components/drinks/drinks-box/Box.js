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
   const [pageLoaded, setPageLoaded] = useState(true);
   const currentPageRef = useRef(1);
   const paginationRef = useRef(true);
   const drinksTotalItemsRef = useRef(0);

   useEffect(() => {
      (async () => {
         setIsLoaded(false);
         setPagination();
         currentPageRef.current = 1;
         const uri = getUri();
         const fetchedDrinks = await fetchDrinks(uri);
         setDrinks(fetchedDrinks);
         setIsLoaded(true);
      })();
   }, [products, categories]);

   const updateDrinks = () => {
      currentPageRef.current++;
      if (paginationRef.current) {
         setPageLoaded(false);
         (async () => {
            const uri = getUri();
            console.log(uri);
            const fetchedDrinks = await fetchDrinks(uri);
            setDrinks([...drinks, ...fetchedDrinks]);
            setPageLoaded(true);
         })();
      } else {
         setDrinks([...drinks]);
      }
   };

   const fetchDrinks = async (uri) => {
      try {
         const response = await axios.get(uri);
         const filteredDrinks = filterDrinks(response.data['hydra:member']);
         drinksTotalItemsRef.current = response.data['hydra:totalItems'];
         console.log('Fetch drinks:', filteredDrinks);
         return filteredDrinks;
      } catch (error) {
         console.log(error);
      }
   };

   const setPagination = () => {
      paginationRef.current = true;

      if (products.length > 0) return (paginationRef.current = false);

      let activeCategories = 0;
      categories.forEach((category) => category.active && activeCategories++);
      if (activeCategories > 0 && activeCategories != categories.length)
         return (paginationRef.current = false);
   };

   const getUri = () => {
      let uri = `/api/drinks?page=${currentPageRef.current}&pagination=${paginationRef.current}&`;
      uri += 'properties[]=id&properties[]=name&properties[]=shortDescription&properties[]=image&';
      uri += 'properties[]=categories&properties[]=products&'

      products.forEach((product) => {
         uri += `products[]=${product.name}&`;
      });

      categories.forEach((category) => {
         category.active && (uri += `categories[]=${category.name}&`);
      });

      return uri;
   };

   const filterDrinks = (data) => {
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
            revelance: productRelevance + categoryRelevance,
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
            drinks={drinks.sort((a, b) => sortFunc(a, b)).slice(0, 20 * currentPageRef.current)}
            setSortFuncBySelectedOption={setSortFuncBySelectedOption}
            isLoaded={isLoaded}
            pageLoaded={pageLoaded}
            drinksTotalItems={drinksTotalItemsRef.current}
            updateDrinks={updateDrinks}
         />
      </div>
   );
};

export default Box;
