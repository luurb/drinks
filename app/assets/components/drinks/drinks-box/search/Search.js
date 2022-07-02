import React from 'react';
import Product from './Product';
import Category from './Category';
import SearchBox from './SearchBox';
import { useRef } from 'react';

const Search = ({ products, setProducts, categories, setCategories }) => {
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
         <SearchBox addProduct={addProduct} products={products} />
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
