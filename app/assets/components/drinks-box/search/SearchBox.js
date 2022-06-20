import React from 'react';
import { useState, useEffect, useRef } from 'react';
import SearchResult from './SearchResult';

const SearchBox = ({ addProduct, savedProducts }) => {
   const productsArr = [
      {
         id: 1,
         name: 'whiskey',
      },
      {
         id: 2,
         name: 'wódka',
      },
      {
         id: 3,
         name: 'sok z cytryny',
      },
      {
         id: 4,
         name: 'sok z limonki',
      },
      {
         id: 5,
         name: 'sok jabłkowy',
      },
      {
         id: 6,
         name: 'sok wiśniowy',
      },
      {
         id: 7,
         name: 'sok bananowy',
      },
      {
         id: 8,
         name: 'sok malinowy',
      },
   ];

   const [products, setProducts] = useState([]);
   const [isOpen, setIsOpen] = useState(false);
   const input = useRef(null);
   const setEvent = useRef(true);

   //Close search box after click somewhere on window
   useEffect(() => {
      if (setEvent.current) {
         window.addEventListener('click', (e) => {
            if (input.current && input.current.contains(e.target)) {
               setIsOpen(!isOpen);
            } else {
               setIsOpen(false);
            }
         });

         //Prevent adding multiple event listeners
         setEvent.current = false;
      }
   });

   const addSearchResult = (value) => {
      setIsOpen(true);
      if (!value) {
         setProducts([]);
         return;
      }
      setProducts(
         productsArr.filter((product) => product.name.includes(value))
      );
   };

   //Clear search box after click on search result
   const onClickSearchResult = (product) => {
      input.current.value = '';
      input.current.focus();
      setProducts([]);
      addProduct(product.name);
   };

   const checkIfAlreadyAdded = (name) => {
      if (savedProducts.length > 0) {
         return savedProducts.some((savedProduct) => savedProduct.name == name);
      }

      return false;
   };

   const productsCounter = 5;

   return (
      <form>
         <label htmlFor="search" className="search__header">
            Wybierz produkty
         </label>
         <div className="search__box">
            <input
               type="text"
               id="search"
               className="search__input"
               placeholder="Wpisz produkt"
               onChange={(e) => {
                  addSearchResult(e.currentTarget.value);
               }}
               ref={input}
            />
            {products.length !== 0 && isOpen && (
               <div className="search__results-box">
                  {products.map(
                     (product, index) =>
                        index < productsCounter &&
                        !checkIfAlreadyAdded(product.name) && (
                           <SearchResult
                              key={product.id}
                              product={product}
                              onClick={onClickSearchResult}
                           />
                        )
                  )}
               </div>
            )}
         </div>
      </form>
   );
};

export default SearchBox;
