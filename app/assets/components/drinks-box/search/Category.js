import React from 'react'

const Category = (props) => {
  return (
    <div className="search__category">
          <span className="search__category-disc" style={{ border: `2px solid var(--${props.color})` }}></span>
          <span className="search__category-text">{props.name}</span>
    </div>
  )
}

export default Category