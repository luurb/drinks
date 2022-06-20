import React from 'react';

const Product = ({product, deleteProduct}) => {
   return (
      <div className="search__product-box">
         <span className="search__product-name">{product.name}</span>
         <i className="fa-solid fa-xmark" onClick={() => deleteProduct(product.id)}></i>
      </div>
   );
};

export default Product;
