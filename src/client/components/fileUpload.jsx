import React, {useCallback} from 'react'
import {useDropzone} from 'react-dropzone'

export default function FileUpload({
    onFiles,
    inactiveText = 'Drag and drop files here, or click to select files',
    activeText = 'Drop files here...',
    style = {},
}) {
  const onDrop = useCallback(acceptedFiles => {
      onFiles(acceptedFiles)
  }, [])
  const {getRootProps, getInputProps, isDragActive} = useDropzone({onDrop})

  return (
    <div {...getRootProps()} style={style}>
      <input {...getInputProps()} />
      {
        <button className="btn btn-primary">
            {
                isDragActive ? activeText : inactiveText
            }
        </button>
      }
    </div>
  )
}
