import React from 'react';

const Product = (props) => {
   return (
      <div className="search__product-box">
         <span className="search__product-name">{props.name}</span>
         <i className="fa-solid fa-xmark"></i>
      </div>
   );
};

export default Product;
