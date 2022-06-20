import React from 'react';
import Product from './Product';
import Category from './Category';
import SearchBox from './SearchBox';
import { useState, useRef } from 'react';

const Search = () => {
   const categories = [
      {
         id: 1,
         name: 'słodki',
         color: 'yellow',
      },
      {
         id: 2,
         name: 'kwaśny',
         color: 'green',
      },
      {
         id: 3,
         name: 'orzeźwiający',
         color: 'blue',
      },
      {
         id: 4,
         name: 'lekki',
         color: 'turquoise',
      },
      {
         id: 5,
         name: 'mocny',
         color: 'red',
      },
   ];

   const [products, setProducts] = useState([]);
   const counterRef = useRef(1);

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
                  name={category.name}
                  color={category.color}
               />
            ))}
         </div>
      </div>
   );
};

export default Search;
