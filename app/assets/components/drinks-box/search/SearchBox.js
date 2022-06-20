import React from 'react';
import { useState } from 'react';
import SearchResult from './SearchResult';

const SearchBox = () => {
   const [products, setProducts] = useState([]);

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

   const addProduct = (value) => {
      if (!value) {
         setProducts([]);
         return;
      }
      setProducts(
         productsArr.filter((product) => product.name.includes(value))
      );
   };

   const productsCounter = 5;

   return (
      <form>
         <label htmlFor="search" className="search__header">
            Wybierz produkty
         </label>
         <div className="search__box">
            <input
               id="search"
               className="search__input"
               placeholder="Wpisz produkt"
               onChange={(e) => {
                  addProduct(e.currentTarget.value);
               }}
            />
            {products.length !== 0 && (
               <div className="search__results-box">
                  {products.map(
                     (product, index) =>
                        index < productsCounter && (
                           <SearchResult
                              key={product.id}
                              name={product.name}
                              index={index}
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
