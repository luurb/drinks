import React from 'react';
import { useState, useEffect, useRef } from 'react';
import SearchResult from './SearchResult';
import axios from 'axios';

const SearchBox = ({ addProduct, products}) => {
   const [isOpen, setIsOpen] = useState(false);
   const input = useRef(null);
   const setEvent = useRef(true);
   const [fetchedProducts, setFetchedProducts] = useState([]);

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
         setFetchedProducts([]);
         return;
      }

      if (value.length > 1) {
         (async function () {
            try {
               const response = await axios.get(`/api/products?name=${value}`, {
                  headers: { accept: 'application/json' },
               });
               setFetchedProducts(checkIfAlreadyAdded(response.data));
            } catch (error) {
               console.log(error);
               setFetchedProducts([]);
            }
         })();
      }
   };

   //Clear search box after click on search result
   const onClickSearchResult = (product) => {
      input.current.value = '';
      input.current.focus();
      setFetchedProducts([]);
      addProduct(product.name);
   };

   //Return products filtered by already selected ones
   const checkIfAlreadyAdded = (data) => {
      return data.filter((product) =>
         !products.some((selected) => selected.name == product.name)
      );
   };

   const productsCounter = 5;

   return (
      <form>
         <label htmlFor="search" className="search__header">
            Wybierz składniki 
         </label>
         <div className="search__box">
            <input
               type="text"
               id="search"
               className="search__input"
               placeholder="Wpisz składnik"
               onChange={(e) => {
                  addSearchResult(e.currentTarget.value);
               }}
               ref={input}
            />
            {fetchedProducts.length !== 0 && isOpen && (
               <div className="search__results-box">
                  {fetchedProducts.map(
                     (product, index) =>
                        index < productsCounter && (
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
