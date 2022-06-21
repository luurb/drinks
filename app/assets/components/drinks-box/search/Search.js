import React from 'react';
import Product from './Product';
import Category from './Category';
import SearchBox from './SearchBox';
import { useState, useEffect, useRef } from 'react';

const Search = ({ setDrinks }) => {
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
   const [products, setProducts] = useState([]);
   const counterRef = useRef(1);
   useEffect(
      () =>
         setDrinks(
            products,
            categories.filter((category) => category.active)
         ),
      [products, categories]
   );

   const addProduct = (name) => {
      setProducts([...products, { id: counterRef.current, name: name }]);
      counterRef.current++;
   };

   const deleteProduct = (id) => {
      setProducts(products.filter((product) => product.id !== id));
   };

   return (
      <div className="search">
         <SearchBox addProduct={addProduct} savedProducts={products} />
         {products.length > 0 && (
            <div className="search__products-wrapper">
               {products.map((product) => (
                  <Product
                     key={product.id}
                     product={product}
                     deleteProduct={deleteProduct}
                  />
               ))}
            </div>
         )}
         <div className="search__categories-box">
            {categories.map((category) => (
               <Category
                  key={category.id}
                  category={category}
                  setCategories={(id) => {
                     setCategories(
                        categories.map((category) =>
                           category.id === id
                              ? { ...category, active: !category.active }
                              : category
                        )
                     );
                  }}
               />
            ))}
         </div>
      </div>
   );
};

export default Search;
