import "./index.scss"
import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon } from "@wordpress/components"

(function ourStartFunction() {
    let locked = false

    wp.data.subscribe(function() {
        const results = wp.data.select("core/block-editor").getBlocks().filter(function (block) {
            // block 중에서 우리가 사용하는 플러그인의 것이고 answer이 undefined인 block만을 가지고 오도록 필터링
            return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined
        })

        if (results.length && !locked) {
            locked = true
            // 편집화면에서 저장을 하지 못하도록 한다.
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (!results.length && locked) {
            locked = false
            // 편집화면에서 저장을 하지 못하도록 한다.
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    })
})()

// ourStartFunction() 같은 함수명과의 충돌을 피할 수 있도록 호출이 아니라 생성과 동시에 실행.

// Block 기반 편집기에 새로운 Block을 추가한다.
// 첫번째 인자 : 새로운 Block의 이름. 일반적으로 "플러그명/블록명" 형식으로 지정.
// 두번째 인자 : Block을 구성하는 속성.
wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
    title: "Are you Paying Attention?",
    icon: "smiley",
    category: "common",
    attributes: {
        question: {type: "string"},
        answers: {type: "array", default: [""]},
        correctAnswer: {type: "number", default: undefined} // '0'가 아닌 undefined로 초기값을 설정한 이유는 boolean 값과 혼동이 일어나지 않기 때문이다.
    },
    // Block이 어떻게 표시되고 동작할지 정의
    edit: EditComponent,
    // Block이 최종적으로 어떻게 저장될지 정의
    save: (props) => {
        return (
            <h6>Today the sky is absolutely<span className="skyColor">{props.attributes.skyColor}</span> and the grass is <span className="grassColor">{props.attributes.grassColor}</span>.</h6>
        )
    }
})

function EditComponent(props) {
  function updateQuestion(value) {
    props.setAttributes({ question: value })
  }

  function deleteAnswer(indexToDelete) {
      // React에서 현존하는 값을 직접 삭제해서는 안된다.
      const newAnswers = props.attributes.answers.filter(function (x, index) {
          // 선택한 항목 외에 모두 true를 반환한다. 즉 선택한 항목을 제외하고 필터링된다.
          return index != indexToDelete
      })

      // 필터링된 배열을 다시 세팅해준다.
      props.setAttributes({answers: newAnswers})

      // 정답인 Answer를 Delete한 경우에는 정답 마크를 초기화 상태로 변경한다.
      if (indexToDelete == props.attributes.correctAnswer) {
          props.setAttributes({correctAnswer: undefined})
      }
  }

    function markAsCorrect(index) {
        props.setAttributes({ correctAnswer: index })
    }

    return (
        <div className="paying-attention-edit-block">
            <TextControl label="Question:" value={props.attributes.question} onChange={updateQuestion} style={{fontSize:"20px"}}/>
            <p style={{fontSize: "13px", margin: "20px 0 8px 0"}}>Answers:</p>
            {props.attributes.answers.map(function (answer, index) {
                return (
                    <Flex>
                        <FlexBlock>
                            {/*React는 DOM 요소를 변경하지 못하게하기 때문에 JSX를 사용하여 변경할 수 있게 만든다.*/}
                            <TextControl autoFocus={answer == undefined} value={answer} onChange={newValue => {
                                // React에서 현존하는 값을 직접 변경해서는 안된다. ex) props.attributes.answer[2] = 'purple'
                                const newAnswers = props.attributes.answers.concat([]); // 복사본을 만들어서 사용한다.
                                newAnswers[index] = newValue;
                                props.setAttributes({answers: newAnswers});
                            }}/>
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index)}>
                                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"}/>
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
                        </FlexItem>
                    </Flex>
                )
            })}
            <Button isPrimary onClick={() => {
                props.setAttributes({answers: props.attributes.answers.concat([undefined])})
            }}>Add another answer</Button>
        </div>
    )
}
