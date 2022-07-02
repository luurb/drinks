import React from 'react';

const SearchResult = ({product, index, onClick}) => {
   return (
      <div
         key={product.id}
         className="search__result"
         onClick={() => onClick(product)}
      >
         {product.name}
      </div>
   );
};

export default SearchResult;
