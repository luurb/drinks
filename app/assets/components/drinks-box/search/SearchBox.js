import React from 'react';
import { useState, useEffect, useRef } from 'react';

const SearchBox = ({ addProduct }) => {
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

   useEffect(() => {
      if (setEvent.current) {
         window.addEventListener('click', (e) => {
            if (input.current && input.current.contains(e.target)) {
               setIsOpen(!isOpen);
            } else {
               setIsOpen(false);
            }
            console.log(isOpen);
         });
         setEvent.current = false;
      }
   });

   const addSearchResult = (value) => {
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
                  addSearchResult(e.currentTarget.value);
               }}
               ref={input}
            />
            {products.length !== 0 && isOpen && (
               <div className="search__results-box">
                  {products.map(
                     (product, index) =>
                        index < productsCounter && (
                           <div
                              key={product.id}
                              className="search__result"
                              style={{
                                 background:
                                    index % 2
                                       ? 'var(--white)'
                                       : 'var(--yellow)',
                              }}
                              onClick={() => {
                                 addProduct(product.name);
                              }}
                           >
                              {product.name}
                           </div>
                        )
                  )}
               </div>
            )}
         </div>
      </form>
   );
};

export default SearchBox;
