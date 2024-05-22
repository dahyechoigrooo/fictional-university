/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.scss":
/*!************************!*\
  !*** ./src/index.scss ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./src/index.scss");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);



(function ourStartFunction() {
  let locked = false;
  wp.data.subscribe(function () {
    const results = wp.data.select("core/block-editor").getBlocks().filter(function (block) {
      // block 중에서 우리가 사용하는 플러그인의 것이고 answer이 undefined인 block만을 가지고 오도록 필터링
      return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined;
    });
    if (results.length && !locked) {
      locked = true;
      // 편집화면에서 저장을 하지 못하도록 한다.
      wp.data.dispatch("core/editor").lockPostSaving("noanswer");
    }
    if (!results.length && locked) {
      locked = false;
      // 편집화면에서 저장을 하지 못하도록 한다.
      wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
    }
  });
})();

// ourStartFunction() 같은 함수명과의 충돌을 피할 수 있도록 호출이 아니라 생성과 동시에 실행.

// Block 기반 편집기에 새로운 Block을 추가한다.
// 첫번째 인자 : 새로운 Block의 이름. 일반적으로 "플러그명/블록명" 형식으로 지정.
// 두번째 인자 : Block을 구성하는 속성.
wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
  title: "Are you Paying Attention?",
  icon: "smiley",
  category: "common",
  attributes: {
    question: {
      type: "string"
    },
    answers: {
      type: "array",
      default: [""]
    },
    correctAnswer: {
      type: "number",
      default: undefined
    } // '0'가 아닌 undefined로 초기값을 설정한 이유는 boolean 값과 혼동이 일어나지 않기 때문이다.
  },
  // Block이 어떻게 표시되고 동작할지 정의
  edit: EditComponent,
  // Block이 최종적으로 어떻게 저장될지 정의
  save: props => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h6", null, "Today the sky is absolutely", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "skyColor"
    }, props.attributes.skyColor), " and the grass is ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "grassColor"
    }, props.attributes.grassColor), ".");
  }
});
function EditComponent(props) {
  function updateQuestion(value) {
    props.setAttributes({
      question: value
    });
  }
  function deleteAnswer(indexToDelete) {
    // React에서 현존하는 값을 직접 삭제해서는 안된다.
    const newAnswers = props.attributes.answers.filter(function (x, index) {
      // 선택한 항목 외에 모두 true를 반환한다. 즉 선택한 항목을 제외하고 필터링된다.
      return index != indexToDelete;
    });

    // 필터링된 배열을 다시 세팅해준다.
    props.setAttributes({
      answers: newAnswers
    });

    // 정답인 Answer를 Delete한 경우에는 정답 마크를 초기화 상태로 변경한다.
    if (indexToDelete == props.attributes.correctAnswer) {
      props.setAttributes({
        correctAnswer: undefined
      });
    }
  }
  function markAsCorrect(index) {
    props.setAttributes({
      correctAnswer: index
    });
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "paying-attention-edit-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: "Question:",
    value: props.attributes.question,
    onChange: updateQuestion,
    style: {
      fontSize: "20px"
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      fontSize: "13px",
      margin: "20px 0 8px 0"
    }
  }, "Answers:"), props.attributes.answers.map(function (answer, index) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Flex, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexBlock, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
      autoFocus: answer == undefined,
      value: answer,
      onChange: newValue => {
        // React에서 현존하는 값을 직접 변경해서는 안된다. ex) props.attributes.answer[2] = 'purple'
        const newAnswers = props.attributes.answers.concat([]); // 복사본을 만들어서 사용한다.
        newAnswers[index] = newValue;
        props.setAttributes({
          answers: newAnswers
        });
      }
    })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      onClick: () => markAsCorrect(index)
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
      className: "mark-as-correct",
      icon: props.attributes.correctAnswer == index ? "star-filled" : "star-empty"
    }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      isLink: true,
      className: "attention-delete",
      onClick: () => deleteAnswer(index)
    }, "Delete")));
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    isPrimary: true,
    onClick: () => {
      props.setAttributes({
        answers: props.attributes.answers.concat([undefined])
      });
    }
  }, "Add another answer"));
}
})();

/******/ })()
;
//# sourceMappingURL=index.js.map